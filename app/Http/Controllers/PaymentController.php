<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe;
use App\Models\Job;
use App\Models\Payment;
use App\Models\CustomerCardDetail;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Auth;

class PaymentController extends Controller
{
    public $gateway;
  
    public function __construct()
    {
        $this->gateway = new AnetAPI\MerchantAuthenticationType();
        $this->gateway->setName(env('ANET_API_LOGIN_ID'));
        $this->gateway->setTransactionKey(env('ANET_TRANSACTION_KEY'));
    }
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripeCharge(Request $request)
    {
        try {
            DB::beginTransaction();
            $loggedUser = $request->user();
            //set stripe env key
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            //stripe charge
            $getPreviousPayment = Payment::whereUserId($loggedUser->id)->first();
            //check if customer exist
            if ($getPreviousPayment == null) {
                //create customer
                $createCustomer = Stripe\Customer::create([
                    "email" => $loggedUser->email
                ]);
                $custId = $createCustomer->id;
            } else {
                $custId = $getPreviousPayment->customer_id;
            }

            //fetch customer first from stripe
            $stripeCustomer = Stripe\Customer::retrieve($custId);
            //check if used card is new card
            if ($request->newCard == 1) {
                //add customer card
                $addCard = Stripe\Customer::createSource(
                    $custId,
                    ['source' => $request->stripeToken]
                );
                //remove previous cards from default
                if (CustomerCardDetail::whereCustomerId($loggedUser->id)->get()->count() > 0) {
                    // CustomerCardDetail::whereCustomerId($loggedUser->id)->update(['card_primary' => 0]);
                    $defaultCard = 0;
                } else {
                    $defaultCard = 1;
                }

                $storeCardDetails = new CustomerCardDetail([
                    'customer_id' => $loggedUser->id,
                    'card_id' => $addCard->id,
                    'card_number' => $addCard->last4,
                    'card_exp_month' => $addCard->exp_month,
                    'card_exp_year' => $addCard->exp_year,
                    'card_status' => 1,
                    'card_primary' => $defaultCard
                ]);
                $storeCardDetails->save();
            }

            //make charge
            $checkStatus = Stripe\Charge::create([
                "amount" => $request->amount*100,
                "currency" => env('STRIPE_CURRENCY'),
                "customer" => $stripeCustomer,
                "description" => "Payment for Job."
            ]);

            if ($checkStatus) {
                $jobPayment = new Payment([
                    'job_id' => $request->job_id,
                    'user_id' => $loggedUser->id,
                    'customer_id' => $custId,
                    'payment_id' => $checkStatus->id,
                    'payment_mode' => $checkStatus->object,
                    'payment_method' => config('constant.payment_methods.stripe'),
                    'currency' => $checkStatus->currency,
                    'amount' => $checkStatus->amount/100,
                    'payment_status' => $checkStatus->status
                ]);

                $jobPayment->save();

                if ($checkStatus->status == config('constant.payment_status.succeeded')) {
                    Job::whereId($request->job_id)->update(['payment_status' => config('constant.payment_status_reverse.succeeded')]);
                }
            }

            DB::commit();

            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Payment successful',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            //make log of errors
            Log::error(json_encode($e->getMessage()));
            //return with error
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }
    }

    /**
     * @method charge : Function to make charge using authorize.net.
     *
     */
    public function charge(Request $request)
    {
        try {
            DB::beginTransaction();

            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($this->gateway);



            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Payment successful',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            //return with error
            return response()->json([
                'status' => false,
                '_m' => $e->getMessage(),
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }
    }

    public function addCard(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id == config('constant.roles.Customer') || $user->role_id == config('constant.roles.Haulers')) {
            $customer = $user;
        } else {
            $farms = $user->farms;
            $customer = $farms[0]->user;
        }

        if(!isset($customer->authorize_net_id) || $customer->authorize_net_id == '') {
            return $this->createCustomerProfile($request, $user, $customer);
        } else {
            return $this->createCustomerPaymentProfile($request, $user, $customer);
        }
    }

    /**
     * @method createCustomerProfile: function to create customer and add card on authorize.net
     *
     */
    public function createCustomerProfile(Request $request, $user, $customer)
    {
        // Set credit card information for payment profile
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($request->card_number);
        $creditCard->setExpirationDate($request->card_exp_year.'-'.$request->card_exp_month);
        $creditCard->setCardCode($request->cvv);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        // Create the Bill To info for new payment type
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($request->name);
        // $billTo->setLastName($user->last_name);
        // $billTo->setCompany("Souveniropolis");
        $billTo->setAddress($user->address);
        $billTo->setCity($user->city);
        $billTo->setState($user->state);
        $billTo->setZip($user->zip_code);
        $billTo->setCountry($user->country);
        $billTo->setPhoneNumber($user->phone);
        // $billTo->setfaxNumber("999-999-9999");

        // Create a customer shipping address
        $customerShippingAddress = new AnetAPI\CustomerAddressType();
        $customerShippingAddress->setFirstName($request->name);
        // $customerShippingAddress->setLastName($user->last_name);
        // $customerShippingAddress->setCompany("Addresses R Us");
        $customerShippingAddress->setAddress($user->address);
        $customerShippingAddress->setCity($user->city);
        $customerShippingAddress->setState($user->state);
        $customerShippingAddress->setZip($user->zip_code);
        $customerShippingAddress->setCountry($user->country);
        $customerShippingAddress->setPhoneNumber($user->phone);
        // $customerShippingAddress->setFaxNumber("999-999-9999");

        // Create an array of any shipping addresses
        $shippingProfiles[] = $customerShippingAddress;


        // Create a new CustomerPaymentProfile object
        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');
        $paymentProfile->setBillTo($billTo);
        $paymentProfile->setPayment($paymentCreditCard);
        $paymentprofile->setDefaultPaymentProfile(true);
        $paymentProfiles[] = $paymentProfile;

        // Create a new CustomerProfileType and add the payment profile object
        $customerProfile = new AnetAPI\CustomerProfileType();
        $customerProfile->setDescription("This is a farm customer.");
        $customerProfile->setMerchantCustomerId($user->id);
        $customerProfile->setEmail($user->email);
        $customerProfile->setpaymentProfiles($paymentProfiles);
        $customerProfile->setShipToList($shippingProfiles);

        // Assemble the complete transaction profileRequest
        $profileRequest = new AnetAPI\CreateCustomerProfileRequest();
        $profileRequest->setMerchantAuthentication($this->gateway);
        $profileRequest->setRefId($customer->id);
        $profileRequest->setProfile($customerProfile);

        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerProfileController($profileRequest);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
    
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $customer->authorize_net_id = $response->getCustomerProfileId();
            $customer->save();
            
            $paymentProfiles = $response->getCustomerPaymentProfileIdList();
            CustomerCardDetail::create([
                'customer_id' => $customer->id,
                'card_id' => $paymentProfiles[0],
                'card_number' => $request->card_number,
                'card_exp_month' => $request->card_exp_month,
                'card_exp_year' => $request->card_exp_year,
                'card_status' => 1,
                'card_primary' => 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Payment successful',
                'data' => $user
            ], 200);
        }
        $errorMessages = $response->getMessages()->getMessage();

        return response()->json([
            'status' => false,
            'message' => 'Please try again later.',
            'error' => $errorMessages,
        ], 421);
    }

    /**
     * @method createCustomerPaymentProfile: Function to createadd card to exsiting customer on authorize.net
     *
     */
    public function createCustomerPaymentProfile(Request $request, $user, $customer)
    {
        if (!$customer->authorize_net_id) {
            return response()->json([
                'status' => false,
                'message' => 'User is not a customer on authorize.net. Please contact admin.',
            ], 421);
        }
        // Set credit card information for payment profile
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($request->card_number);
        $creditCard->setExpirationDate($request->card_exp_year.'-'.$request->card_exp_month);
        $creditCard->setCardCode($request->cvv);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        // Create the Bill To info for new payment type
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($request->name);
        $billTo->setAddress($user->address);
        $billTo->setCity($user->city);
        $billTo->setState($user->state);
        $billTo->setZip($user->zip_code);
        $billTo->setCountry($user->country);
        $billTo->setPhoneNumber($user->phone);

        // Create a new Customer Payment Profile object
        $paymentprofile = new AnetAPI\CustomerPaymentProfileType();
        $paymentprofile->setCustomerType('individual');
        $paymentprofile->setBillTo($billTo);
        $paymentprofile->setPayment($paymentCreditCard);
        $paymentprofile->setDefaultPaymentProfile(false);

        $paymentprofiles[] = $paymentprofile;

        // Assemble the complete transaction request
        $paymentprofilerequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
        $paymentprofilerequest->setMerchantAuthentication($this->gateway);

        // Add an existing profile id to the request
        $paymentprofilerequest->setCustomerProfileId($customer->authorize_net_id);
        $paymentprofilerequest->setPaymentProfile($paymentprofile);
        // $paymentprofilerequest->setValidationMode("liveMode");

        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $paymentProfileId = $response->getCustomerPaymentProfileId();
            CustomerCardDetail::create([
                'customer_id' => $customer->id,
                'card_id' => $paymentProfileId,
                'card_number' => $request->card_number,
                'card_exp_month' => $request->card_exp_month,
                'card_exp_year' => $request->card_exp_year,
                'card_status' => 1,
                'card_primary' => 0,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Payment successful',
                'data' => $user
            ], 200);

        } else {
            $errorMessages = $response->getMessages()->getMessage();
            return response()->json([
                'status' => false,
                'message' => 'Please try again later.',
                'error' => $errorMessages
            ], 421);
        }
    }

    function getCustomerPaymentProfileList()
    {
        $cards = CustomerCardDetail::where('customer_id', Auth::user()->id)->get();

        return response()->json([
            'status' => true,
            'data' => $cards
        ], 200);
    }

    function deleteCustomerPaymentProfile(CustomerCardDetail $customerCardDetails) 
    {
        if ($customerCardDetails->card_primary == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Can not delete a default card.',
            ], 421);
        }
        $user = Auth::user();
        // dd($customerCardDetails->customer);
        // if (!$user->canAccessFarm()) {

        // }
        if ($user->role_id == config('constant.roles.Customer') || $user->role_id == config('constant.roles.Haulers')) {
            $customerProfileId = ($user->authorize_net_id) ? $user->authorize_net_id : null;
        } else {
            $farms = $user->farms;
            $customerProfileId = ($farms[0] && $farms[0]->user && $farms[0]->user->authorize_net_id ) ? $farms[0]->user->authorize_net_id : null;
        }

        if (!$customerProfileId) {
            return response()->json([
                'status' => false,
                'message' => 'User is not a customer on authorize.net. Please contact admin.',
            ], 421);
        }
        
        $request = new AnetAPI\DeleteCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($this->gateway);
        $request->setCustomerProfileId($customerProfileId);
        $request->setCustomerPaymentProfileId($customerCardDetails->card_id);
        $controller = new AnetController\DeleteCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
        {
            $customerCardDetails->delete();

            return response()->json([
                'status' => true,
                'message' => 'Card deleted successfully.'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Not able to delete card please try again later.',
                'error' => $errorMessages = $response->getMessages()->getMessage()
            ], 421);
        }
    }

    function updateCustomerPaymentProfile(CustomerCardDetail $customerCardDetails) 
    {
        $user = Auth::user();
        if ($user->role_id == config('constant.roles.Customer') || $user->role_id == config('constant.roles.Haulers')) {
            $customer = $user;
        } else {
            $farms = $user->farms;
            $customer = $farms[0]->user;
        }

        $request = new AnetAPI\GetCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($this->gateway);
        $request->setRefId($customer->id);
        $request->setCustomerProfileId($customer->authorize_net_id);
        $request->setCustomerPaymentProfileId($customerCardDetails->card_id);
        
        $controller = new AnetController\GetCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($customerCardDetails->card_number);
            $exp = $customerCardDetails->card_exp_year . '-' . (($customerCardDetails->card_exp_month < 10) ? '0'.$customerCardDetails->card_exp_month : $customerCardDetails->card_exp_month);
            $creditCard->setExpirationDate($exp);
            $paymentCreditCard = new AnetAPI\PaymentType();
            $paymentCreditCard->setCreditCard($creditCard);

            $paymentprofile = new AnetAPI\CustomerPaymentProfileExType();
            $paymentprofile->setCustomerPaymentProfileId($customerCardDetails->card_id);
            $paymentprofile->setDefaultPaymentProfile(true);
            $paymentprofile->setPayment($paymentCreditCard);

            // Submit a UpdatePaymentProfileRequest
            $request = new AnetAPI\UpdateCustomerPaymentProfileRequest();
            $request->setMerchantAuthentication($this->gateway);
            $request->setCustomerProfileId($customer->authorize_net_id);
            $request->setPaymentProfile($paymentprofile);

            $controller = new AnetController\UpdateCustomerPaymentProfileController($request);
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
            {
                CustomerCardDetail::where([
                    'customer_id' => $customer->id,
                    'card_primary' => 1
                ])->update([
                    'card_primary' => 0
                ]);
                $customerCardDetails->update([
                    'card_primary' => 1
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Card set as default card.'
                ], 200);
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Failed to set card as default card.',
            'error' => $response->getMessages()->getMessage()
        ], 421);
    }

}
