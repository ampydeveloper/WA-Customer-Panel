<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Validator;
use Mail;
use App\Models\User;
use App\Models\Service;
use App\Models\TimeSlots;
use App\Models\Job;
use App\Models\CustomerFarm;
use App\Http\Requests\Job\{
    CreateJobRequest
};
class JobController extends Controller {
  
    /**
     * @method createJob : Function to Create job.
     */
    public function create(CreateJobRequest $createJobRequest)
    {
        $farm = CustomerFarm::find($createJobRequest->farm_id);
        if (!$farm->isOwner()) {
            return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                ], 421);
        }
        $checkService = Service::where('id', $createJobRequest->service_id)->first();
        DB::beginTransaction();
        try {
            $job = new Job([
                'job_created_by' => $createJobRequest->user()->id,
                'customer_id' => $createJobRequest->user()->id,
                'manager_id' => (isset($createJobRequest->manager_id) && $createJobRequest->manager_id != '' && $createJobRequest->manager_id != null) ? $createJobRequest->manager_id : null,
                'farm_id' => (isset($createJobRequest->farm_id) && $createJobRequest->farm_id != '' && $createJobRequest->farm_id != null) ? $createJobRequest->farm_id : null,
                'service_id' => $createJobRequest->service_id,
                'gate_no' => (isset($createJobRequest->gate_no) && $createJobRequest->gate_no != '' && $createJobRequest->gate_no != null) ? $createJobRequest->gate_no : null,
                'time_slots_id' => (isset($createJobRequest->time_slots_id) && $createJobRequest->time_slots_id != '' && $createJobRequest->time_slots_id != null) ? $createJobRequest->time_slots_id : null,
                'job_providing_date' => $createJobRequest->job_providing_date,
                'weight' => (isset($createJobRequest->weight) && $createJobRequest->weight != '' && $createJobRequest->weight != null) ? $createJobRequest->weight : null,
                'is_repeating_job' => $createJobRequest->is_repeating_job,
                'repeating_days' => (isset($createJobRequest->repeating_days) && $createJobRequest->repeating_days != '' && $createJobRequest->repeating_days != null) ? $createJobRequest->repeating_days : null,
                'payment_mode' => (isset($createJobRequest->payment_mode) && $createJobRequest->payment_mode != '' && $createJobRequest->payment_mode != null) ? $createJobRequest->payment_mode : 3,
                'images' => (isset($createJobRequest->images) && $createJobRequest->images != '' && $createJobRequest->images != null) ? $createJobRequest->images : null,
                'notes' => (isset($createJobRequest->notes) && $createJobRequest->notes != '' && $createJobRequest->notes != null) ? $createJobRequest->notes : null,
                'amount' => $createJobRequest->amount,
            ]);
            if ($job->save()) {
                $mailData = [
                    'job_id' => $job->id,
                    'customer_id' => $job->customer_id,
                    'manager_id' => isset($job->manager_id) ? $job->manager_id : null
                ];
                $this->_sendPaymentEmail($mailData);
                return response()->json([
                            'status' => true,
                            'message' => 'Job created successfully.',
                            'data' => []
                        ], 200);
            }
            DB::commit();

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
    public function _sendPaymentEmail($mailData) {
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

    /**
     * get farms
     */
    public function getJobFrams(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer Details',
                    'data' => CustomerFarm::where('customer_id', $request->customer_id)->where('farm_active', '1')->with('farmManager')->get()
                        ], 200);
    }
    /**
     * get service time slots
     */
    public function getServiceSlots(Request $request) {
        $service = Service::whereId($request->service_id)->first();
        if ($service != null) {
            $timeSlots = TimeSlots::whereIn('id', json_decode($service->slot_time))->get();
            return response()->json([
                        'status' => true,
                        'message' => 'Service Slot Details',
                        'data' => $timeSlots
                            ], 200);
        } else {
            return response()->json([
                        'status' => true,
                        'message' => 'No time slot available',
                        'data' => []
                            ], 500);
        }
    }
    /**
     * get single jobs
     */
    public function getSingleJob(Request $request) {
        $getSingleJobs = Job::whereId($request->job_id)->with("customer", "manager", "farm", "service", "timeslots", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->first();
        return response()->json([
                    'status' => true,
                    'message' => 'single job Details',
                    'data' => $getSingleJobs
                        ], 200);
    }
    
    
    public function cancelJob(Request $request) {
        $validator = Validator::make($request->all(), [
                    'job_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $bookedService = Job::where('id', $request->job_id)->first();
        if (round((strtotime($bookedService->job_providing_date) - strtotime(date('Y/m/d'))) / 3600, 1) >= 24) {
            try {
                Job::whereId($request->job_id)->update(['job_status' => config('constant.job_status.cancelled')]);
                Job::whereId($request->job_id)->delete();
                return response()->json([
                            'status' => true,
                            'message' => 'Job deleted successfully',
                            'data' => []
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

}
