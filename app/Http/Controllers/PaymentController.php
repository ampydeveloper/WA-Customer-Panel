<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe;
use App\Job;
use App\Payment;
use App\CustomerCardDetail;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

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
            if($getPreviousPayment == null) {
                //create customer
                $createCustomer = Stripe\Customer::create ([
                    "email" => $loggedUser->email 
                ]);
                $custId = $createCustomer->id;
            } else {
                $custId = $getPreviousPayment->customer_id;
            }

            //fetch customer first from stripe
            $stripeCustomer = Stripe\Customer::retrieve ($custId);
            //check if used card is new card
            if($request->newCard == 1) {
                //add customer card
                $addCard = Stripe\Customer::createSource(
                    $custId,
                    ['source' => $request->stripeToken]
                );
                //remove previous cards from default 
                if(CustomerCardDetail::whereCustomerId($loggedUser->id)->get()->count() > 0){
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
            $checkStatus = Stripe\Charge::create ([
                "amount" => $request->amount*100,
                "currency" => env('STRIPE_CURRENCY'),
                "customer" => $stripeCustomer,
                "description" => "Payment for Job." 
            ]);

            if($checkStatus) {
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

                if($checkStatus->status == config('constant.payment_status.succeeded')) {
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
}
