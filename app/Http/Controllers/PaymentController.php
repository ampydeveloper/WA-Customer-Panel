<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Auth;
use App\Models\{
    Job,
    User,
    Payment,
    CustomerCardDetail
};
use App\Http\Requests\Payment\ {
    ChargeCustomerProfileRequest
};

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

    public function checkCardExist($cardNumber, $customer_id)
    {
        return CustomerCardDetail::where('card_number', $cardNumber)->where('customer_id', $customer_id)->first();
    }

    public function addCard(Request $request)
    {
        $status = $this->processAddCard($request->all());
        if ($status['status']) {
            return response()->json($status, 200);
        } else {
            return response()->json($status, 200);
        }
    }

    public function processAddCard($cardData)
    {
        $user = Auth::user();

        if ($user->role_id == config('constant.roles.Customer') || $user->role_id == config('constant.roles.Haulers')) {
            $customer = $user;
        } else {
            $farms = $user->farms;
            $customer = $farms[0]->user;
        }
        if ($this->checkCardExist($cardData['card_number'], $customer->id)) {
            return [
                'status' => false,
                'message' => "This card already exist."
            ];
        }

        if(!isset($customer->authorize_net_id) || $customer->authorize_net_id == '') {
            return $this->createCustomerProfile($cardData, $user, $customer);
        } else {
            return $this->createCustomerPaymentProfile($cardData, $user, $customer);
        }
    }

    /**
     * @method createCustomerProfile: function to create customer and add card on authorize.net
     *
     */
    public function createCustomerProfile(array $cardData, $user, $customer)
    {
        $cardData = (object) $cardData;
        // Set credit card information for payment profile
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardData->card_number);
        $creditCard->setExpirationDate($cardData->card_exp_year.'-'.$cardData->card_exp_month);
        $creditCard->setCardCode($cardData->cvv);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        // Create the Bill To info for new payment type
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($cardData->name);
        // $billTo->setLastName($customer->last_name);
        // $billTo->setCompany("Souveniropolis");
        $billTo->setAddress($customer->address);
        $billTo->setCity($customer->city);
        $billTo->setState($customer->state);
        $billTo->setZip($customer->zip_code);
        $billTo->setCountry($customer->country);
        $billTo->setPhoneNumber($customer->phone);
        // $billTo->setfaxNumber("999-999-9999");

        // Create a customer shipping address
        $customerShippingAddress = new AnetAPI\CustomerAddressType();
        $customerShippingAddress->setFirstName($cardData->name);
        // $customerShippingAddress->setLastName($customer->last_name);
        // $customerShippingAddress->setCompany("Addresses R Us");
        $customerShippingAddress->setAddress($customer->address);
        $customerShippingAddress->setCity($customer->city);
        $customerShippingAddress->setState($customer->state);
        $customerShippingAddress->setZip($customer->zip_code);
        $customerShippingAddress->setCountry($customer->country);
        $customerShippingAddress->setPhoneNumber($customer->phone);
        // $customerShippingAddress->setFaxNumber("999-999-9999");

        // Create an array of any shipping addresses
        $shippingProfiles[] = $customerShippingAddress;


        // Create a new CustomerPaymentProfile object
        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');
        $paymentProfile->setBillTo($billTo);
        $paymentProfile->setPayment($paymentCreditCard);
        $paymentProfile->setDefaultPaymentProfile(true);
        $paymentProfiles[] = $paymentProfile;

        // Create a new CustomerProfileType and add the payment profile object
        $customerProfile = new AnetAPI\CustomerProfileType();
        $customerProfile->setDescription("This is a farm customer.");
        $customerProfile->setMerchantCustomerId($customer->id);
        $customerProfile->setEmail($customer->email);
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
            $card = CustomerCardDetail::create([
                'name' => $cardData->name,
                'customer_id' => $customer->id,
                'card_id' => $paymentProfiles[0],
                'card_number' => $cardData->card_number,
                'card_exp_month' => $cardData->card_exp_month,
                'card_exp_year' => $cardData->card_exp_year,
                'card_status' => 1,
                'card_primary' => 1,
            ]);

            return [
                'status' => true,
                'message' => 'Card added successfully.',
                'data' => $user,
                'card' => $card
            ];
        }
        $errorMessages = $response->getMessages()->getMessage();

        return [
            'status' => false,
            'message' => 'Please try again later.',
            'error' => $errorMessages
        ];
    }

    /**
     * @method createCustomerPaymentProfile: Function to createadd card to exsiting customer on authorize.net
     *
     */
    public function createCustomerPaymentProfile(array $cardData, $user, $customer)
    {
        $cardData = (object) $cardData;
        if (!$customer->authorize_net_id) {
            return response()->json([
                'status' => false,
                'message' => 'User is not a customer on authorize.net. Please contact admin.',
            ], 421);
        }
        // Set credit card information for payment profile
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardData->card_number);
        $creditCard->setExpirationDate($cardData->card_exp_year.'-'.$cardData->card_exp_month);
        $creditCard->setCardCode($cardData->cvv);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        // Create the Bill To info for new payment type
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($cardData->name);
        $billTo->setAddress($customer->address);
        $billTo->setCity($customer->city);
        $billTo->setState($customer->state);
        $billTo->setZip($customer->zip_code);
        $billTo->setCountry($customer->country);
        $billTo->setPhoneNumber($customer->phone);

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
            
            $card = CustomerCardDetail::create([
                'name' => $cardData->name,
                'customer_id' => $customer->id,
                'card_id' => $paymentProfileId,
                'card_number' => $cardData->card_number,
                'card_exp_month' => $cardData->card_exp_month,
                'card_exp_year' => $cardData->card_exp_year,
                'card_status' => 1,
                'card_primary' => 0,
            ]);

            return [
                'status' => true,
                'message' => 'Card added successfully.',
                'data' => $user,
                'card' => $card
            ];

        } else {
            $errorMessages = $response->getMessages()->getMessage();
            return [
                'status' => false,
                'message' => 'Please try again later.',
                'error' => $errorMessages
            ];
        }
    }

    /**
     * @method getCustomerPaymentProfileList : Function to get all cards.
     *
     */
    function getCustomerPaymentProfileList()
    {
        $cards = CustomerCardDetail::where('customer_id', Auth::user()->id)->get();

        return response()->json([
            'status' => true,
            'data' => $cards
        ], 200);
    }

    /**
     * @method deleteCustomerPaymentProfile : Function to delete a card.
     *
     */
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

    /**
     * @method updateCustomerPaymentProfile : Function to make card default.
     *
     */
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

    /**
     * @method chargeCustomerProfile : Function to charge a customer.
     *
     */
    function chargeCustomerProfile(ChargeCustomerProfileRequest $request)
    {
        $amount = $request->amount;
        $customer = User::find($request->customer_id);

        $job = Job::find($request->job_id);
        $farm = $job->farm;
        if ($farm->customer_id != $customer->id) {
            return response()->json([
                'status' => false,
                'message' => $customer->full_name.' is not the owner of farm.'
            ], 421);
        }
        if (!$customer->authorize_net_id || $customer->authorize_net_id == '') {
            return response()->json([
                'status' => false,
                'message' => 'Customer is not registered on authrized.net.'
            ], 421);
        }

        if ($job->card_id) {
            $defaultCard = CustomerCardDetail::find($job->card_id);
        } else {
            $defaultCard = $customer->defaultCard();
            if (!$defaultCard) {
                return response()->json([
                    'status' => false,
                    'message' => 'Customer do not have any default card.'
                ], 421);
            }
        }
        
        
        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($customer->authorize_net_id);
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($defaultCard->card_id);
        $profileToCharge->setPaymentProfile($paymentProfile);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType( "authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setProfile($profileToCharge);

        $createTransactionRequest = new AnetAPI\CreateTransactionRequest();
        $createTransactionRequest->setMerchantAuthentication($this->gateway);
        $createTransactionRequest->setRefId($customer->id);
        $createTransactionRequest->setTransactionRequest( $transactionRequestType);
        $controller = new AnetController\CreateTransactionController($createTransactionRequest);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $errorMessage = '';
        $errorCode = '';
        if ($response != null) {
            if($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {

                    Payment::create([
                        'job_id' => $request->job_id,
                        'user_id' => $request->user_id,
                        'customer_id' => $request->customer_id,
                        'payment_id' => $tresponse->getTransId(),
                        'payment_mode' => config('constant.payment_mode.online'),
                        'payment_method' => config('constant.payment_methods.authorizenet'),
                        'currency' => 'USD',
                        'amount' => $request->amount,
                        'payment_status' => config('constant.payment_status_reverse.succeeded')
                    ]);
                    return response()->json([
                        'status' => true,
                        'message' => 'Payment done successfully.',
                        'data' => [
                            'payment_id' => $tresponse->getTransId(),
                            'amount' => $request->amount
                        ]
                    ], 200);
                } else {
                    if ($tresponse->getErrors() != null) {
                        $errorMessage = $tresponse->getErrors()[0]->getErrorText();
                        $errorCode = $tresponse->getErrors()[0]->getErrorCode();
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Payment failed.',
                        'error' => [
                            'errorMessage' => $errorMessage,
                            'errorCode' => $errorCode
                        ]
                        ], 421);
                }
            } else {
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $errorMessage = $tresponse->getErrors()[0]->getErrorText();
                    $errorCode = $tresponse->getErrors()[0]->getErrorCode();
                } else {
                    $errorMessage = $response->getMessages()->getMessage()[0]->getText();
                    $errorCode = $response->getMessages()->getMessage()[0]->getCode();
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Payment failed.',
                    'error' => [
                        'errorMessage' => $errorMessage,
                        'errorCode' => $errorCode
                    ]
                ], 421);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Payment failed.',
            'error' => [
                'errorMessage' => 'No response returned.'
            ]
        ], 421);
    }

}
