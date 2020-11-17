<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
use Validator;
use App\Models\Job;
use App\Models\User;
//use App\Models\Service;
//use App\Models\TimeSlots;
//use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerFarm;
use Illuminate\Support\Carbon;
use App\Models\CustomerCardDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PaymentController;
use App\Http\Requests\Job\{
    CreateJobRequest
};

class JobController extends Controller
{
    
    public function myJobs()
    {
        return response()->json([
            'status' => true,
            'message' => 'Job List',
            'data' => Auth::user()->myJobs()
        ], 200);
    }
    
    public function myUpcomingJobs()
    {
        return response()->json([
            'status' => true,
            'message' => 'Job List',
            'data' => Auth::user()->myUpcomingJobs()
        ], 200);
    }

    public function create(CreateJobRequest $createJobRequest)
    {
        $user = Auth::user();
        if($user->role_id == config('constant.roles.Customer') || $user->role_id == config('constant.roles.Haulers')) {
            if($user->authorize_net_id == null) {
                return response()->json([
                    'status' => false,
                    'message' => 'No card added',
                    'data' => []
                        ], 421);
            }
        } else {
            $Owner = User::whereId($user->created_by)->first();
            if($Owner->authorize_net_id == null) {
                return response()->json([
                    'status' => false,
                    'message' => 'No card added',
                    'data' => []
                        ], 421);
            }
        }
        DB::beginTransaction();
        try {
            $data = [
                'job_created_by' => $user->id,
                'card_id' => null,
                'service_id' => $createJobRequest->service_id,
                'gate_no' => (isset($createJobRequest->gate_no) && $createJobRequest->gate_no != '' && $createJobRequest->gate_no != null) ? $createJobRequest->gate_no : null,
                'time_slots_id' => (isset($createJobRequest->time_slots_id) && $createJobRequest->time_slots_id != '' && $createJobRequest->time_slots_id != null) ? $createJobRequest->time_slots_id : null,
                'job_providing_date' => $createJobRequest->job_providing_date,
                'weight' => (isset($createJobRequest->weight) && $createJobRequest->weight != '' && $createJobRequest->weight != null) ? $createJobRequest->weight : null,
                'is_repeating_job' => ($createJobRequest->is_repeating_job == false) ? 1 : 2,
                'repeating_days' => (isset($createJobRequest->repeating_days) && $createJobRequest->repeating_days != '' && $createJobRequest->repeating_days != null) ? json_encode(explode(',', $createJobRequest->repeating_days)) : null,
                'payment_mode' => $createJobRequest->payment_mode,
                'notes' => (isset($createJobRequest->notes) && $createJobRequest->notes != '' && $createJobRequest->notes != null) ? $createJobRequest->notes : null,
                'amount' => $createJobRequest->amount,
            ];
            if($user->role_id == config('constant.roles.Customer')) {
                $data['farm_id'] = $createJobRequest->farm_id;
                $data['customer_id'] = $user->id;
                $data['manager_id'] = $createJobRequest->manager_id;
            } else if($user->role_id == config('constant.roles.Customer_Manager')) {
                $data['farm_id'] = $user->farm_id;
                $data['customer_id'] = $user->created_by;
                $data['manager_id'] = $user->id;
            } else if($user->role_id == config('constant.roles.Haulers')) {
                $data['farm_id'] = null;
                $data['customer_id'] = $user->id;
                $data['manager_id'] = $createJobRequest->manager_id;
            } else {
                $data['farm_id'] = null;
                $data['customer_id'] = $user->created_by;
                $data['manager_id'] = $user->id;
            }

            $job = new Job($data);
            if ($job->save()) {
                if (isset($createJobRequest->images) && $createJobRequest->images && count($createJobRequest->images) > 0) {
                    $jobImages = [];
                    foreach ($createJobRequest->images as $image) {
                        $imageName = $job->putImage($image);
                        if ($imageName) {
                            $jobImages[] = $imageName;
                        }
                    }
                    $job->update(['images' => json_encode($jobImages)]);
                }

                if ($createJobRequest->attach_card == 1) {
                    $payment = new PaymentController();
                    $cardExist = CustomerCardDetail::where([
                        'card_number' => $createJobRequest->card['card_number'],
                        'card_exp_month' => $createJobRequest->card['card_exp_month'],
                        'card_exp_year' => $createJobRequest->card['card_exp_year']
                    ])->whereNull('deleted_at')->first();
                    if (!$cardExist) {
                        $addCardProcess = $payment->processAddCard($createJobRequest->card);
                        if ($addCardProcess['status']) {
                            $cardExist = $addCardProcess['card'];
                        } else {
                            return response()->json($addCardProcess, 200);
                            throw new \Exception('Not able to add card');
                        }
                    }
                    $job->card_id = $cardExist->id;
                    $job->save();
                }
                $mailData = [
                    'job_id' => $job->id,
                    'customer_id' => $job->customer_id,
                    'manager_id' => isset($job->manager_id) ? $job->manager_id : null
                ];
                $this->_sendPaymentEmail($mailData);
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Job created successfully.',
                    'job_id' => $job->id
                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * @method _sendPaymentEmail : Function to send job booking email.
     */
    public function _sendPaymentEmail($mailData)
    {
        $customerDetails = User::whereId($mailData['customer_id'])->first();
        $customerName = $customerDetails->first_name . ' ' . $customerDetails->last_name;
        $data = array(
            'user' => $customerDetails,
            'name' => $customerName,
        );
        //send to customer
        Mail::send('email_templates.payment_email', $data, function ($message) use ($customerDetails, $customerName) {
            $message->to($customerDetails->email, $customerName)->subject('Job Created');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
        //send to manager
        if ($mailData['manager_id'] !== null) {
            $managerDetails = User::whereId($mailData['manager_id'])->first();
            $managerName = $managerDetails->first_name . ' ' . $managerDetails->last_name;
            $data = array(
                'user' => $managerDetails,
                'name' => $managerName,
            );
            Mail::send('email_templates.payment_email', $data, function ($message) use ($managerDetails, $managerName) {
                $message->to($managerDetails->email, $managerName)->subject('Job Created');
                $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
            });
        }
    }
    
    public function update(Job $job_id, Request $request)
    {
        $validator = Validator::make(array_merge(['job_id' => $job_id], $request->all()), [
                    'manager_id' => 'sometimes|required',
                    'farm_id' => 'sometimes|required',
                    'service_id' => 'required',
                    'job_providing_date' => 'required',
                    'is_repeating_job' => 'required',
                    'payment_mode' => 'required',
                    'repeating_days' => 'required_if:is_repeating_job,==,true',
            
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        
        if ($job_id->job_status == config('constant.job_status.open')) {
            try {
                $images = null;
                if(isset($request->existingImages) && $request->existingImages && strlen($request->existingImages) > 0){
                    $images = explode(',', $request->existingImages);
                }
                if (isset($request->images) && $request->images && count($request->images) > 0) {
                    $jobImages = [];
                    foreach ($request->images as $image) {
                        $imageName = $job_id->putImage($image);
                        if ($imageName) {
                            $jobImages[] = $imageName;
                        }
                    }
                    if($images != null){
                        $jobImages = array_merge($images, $jobImages);
                    }
                    $images = json_encode($jobImages);
                }
                $jobUpdate = [
                    'gate_no' => (isset($request->gate_no) && $request->gate_no != '' && $request->gate_no != null) ? $request->gate_no : null,
                    'service_id' => $request->service_id,
                    'time_slots_id' => (isset($request->time_slots_id) && $request->time_slots_id != '' && $request->time_slots_id != null) ? $request->time_slots_id : null,
                    'job_providing_date' => $request->job_providing_date,
                    'weight' => (isset($request->weight) && $request->weight != '' && $request->weight != null) ? $request->weight : null,
                    'is_repeating_job' => ($request->is_repeating_job == false) ? 1 : 2,
                    'repeating_days' => (isset($request->repeating_days) && $request->repeating_days != '' && $request->repeating_days != null) ? json_encode(explode(',', $request->repeating_days)) : null,
                    'payment_mode' => (isset($request->payment_mode) && $request->payment_mode != '' && $request->payment_mode != null) ? $request->payment_mode : 3,
                    'images' => (isset($request->images) && $request->images != '' && $request->images != null) ? $request->images : null,
                    'notes' => (isset($request->notes) && $request->notes != '' && $request->notes != null) ? $request->notes : null,
                    'amount' => $request->amount,
                    'images' => $images
                ];
                if(isset($request->manager_id)) {
                    $jobUpdate['manager_id'] = $request->manager_id;
                }
                if(isset($request->farm_id)) {
                    $jobUpdate['farm_id'] = $request->farm_id;
                }
                $job_id->update($jobUpdate);
                $mailData = [
                    'job_id' => $job_id->id,
                    'customer_id' => $job_id->customer_id,
                    'manager_id' => isset($request->manager_id) ? $request->manager_id : null
                ];
                $this->_sendPaymentEmail($mailData);
                return response()->json([
                            'status' => true,
                            'message' => 'Job updated successfully.',
                            'data' => []
                        ], 200);
            } catch (\Exception $e) {
                return response()->json([
                            'status' => false,
                            'message' => $e->getMessage(),
                            'data' => []
                        ], 500);
            }
        }
        return response()->json([
                    'status' => false,
                    'message' => 'You cannot edit the job.',
                    'data' => []
                ], 500);
    }

    
    public function cancelJob(Job $job)
    {
        $user = Auth::user();
        If ($user->id == $job->customer_id || $user->id == $job->manager_id || ($user->farm_id == $job->farm_id && $user->role_id == config('constant.roles.Customer_Manager'))) {
            if ($job->job_status == config('constant.job_status.open')) {
                try {
                    $job->update(['job_status' => config('constant.job_status.cancelled')]);
                    return response()->json([
                                'status' => true,
                                'message' => 'Job cancelled successfully.',
                                'data' => $job
                                    ], 200);
                } catch (\Exception $e) {
                    Log::error(json_encode($e->getMessage()));
                    return response()->json([
                                'status' => false,
                                'message' => $e->getMessage(),
                                'data' => []
                                    ], 500);
                }
            }
            return response()->json([
                        'status' => false,
                        'message' => 'You cannot cancel the job.',
                        'data' => []
                            ], 500);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'unauthorized access.',
                    'data' => []
                        ], 421);
    }
    
    public function get(Job $job)
    {
        $user = Auth::user();
        If ($user->id == $job->customer_id || $user->id == $job->manager_id || ($user->farm_id == $job->farm_id && $user->role_id == config('constant.roles.Customer_Manager'))) {
            return response()->json([
                        'status' => true,
                        'message' => 'Job Details.',
                        'data' => $job->where('id', $job->id)->with('farm', 'manager', 'service')->first()
                            ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'unauthorized access.',
                    'data' => []
                        ], 421);
    }

    public function getJobsOfFram(CustomerFarm $customerFarm)
    {
        if (!Auth::user()->canAccessFarm($customerFarm)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.',
                'data' => []
            ], 421);
        }

        return response()->json([
            'status' => true,
            'message' => 'Job List Of Farm',
            'data' => $customerFarm->jobs
        ], 200);
    }
    
    public function upcomingJobsOfFarm(CustomerFarm $customerFarm)
    {
        if (!Auth::user()->canAccessFarm($customerFarm)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.',
                'data' => []
            ], 421);
        }

        return response()->json([
            'status' => true,
            'message' => 'Upcoming Jobs',
            'now' => Carbon::now()->format('Y-m-d'),
            'data' => Job::Where('farm_id', $customerFarm->id)->where('job_providing_date', '>', Carbon::now())->with('truck_driver', 'truck')->get()
        ], 200);
    }
    
    public function chatMembers(Request $request) {
        die('red');
        $chatMembers = Job::whereId($request->job_id)->with(['customer' => function($q) {
            $q->select('id', 'first_name', 'user_image');
        }]) ->with(['manager' => function($q) {
        
            $q->select('id', 'first_name', 'user_image');
        }])->with(['truck_driver' => function($q) {
        
            $q->select('id', 'first_name', 'user_image');
        }])->with(['skidsteer_driver' => function($q) {
        
            $q->select('id', 'first_name', 'user_image');
        }])->first();
        return response()->json([
                    'status' => true,
                    'message' => 'Chat members',
                    'data' => $chatMembers
                        ], 200);
    }
    
    public function jobChat(Request $request) {
        $data = array(
            'jobId' => $request->job_id,
        );
        $postData = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://" . env('SOCKET_SERVER_IP') . ":" . env('SOCKET_SERVER_PORT') . "/job-chat");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $output = curl_exec($ch);
        curl_close($ch);
        $messages = array_reverse(json_decode($output));
        
            return response()->json([
                    'status' => true,
                    'message' => 'Chat messages',
                    'data' => $messages
                        ], 200);
            
    }
    
//    public function getServiceSlots(Request $request)
//    {
//        $service = Service::whereId($request->service_id)->first();
//        if ($service != null) {
//            $timeSlots = TimeSlots::whereIn('id', json_decode($service->slot_time))->get();
//            return response()->json([
//                'status' => true,
//                'message' => 'Service Slot Details.',
//                'data' => $timeSlots
//            ], 200);
//        } else {
//            return response()->json([
//                'status' => true,
//                'message' => 'No time slot available.',
//                'data' => []
//            ], 500);
//        }
//    }
}
