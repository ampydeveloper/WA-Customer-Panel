<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Validator;
use Mail;
use Auth;
use App\Models\User;
use App\Models\Service;
use App\Models\TimeSlots;
use App\Models\Job;
use App\Models\CustomerFarm;
use App\Models\CustomerCardDetail;
use App\Http\Controllers\PaymentController;
use App\Http\Requests\Job\{
    CreateJobRequest
};

class JobController extends Controller
{

    /**
     * @method createJob : Function to Create job.
     */
    public function create(CreateJobRequest $createJobRequest)
    {
        
//        dd($createJobRequest->all());
        $validator = Validator::make($createJobRequest->all(), [
                    'manager_id' => 'required',
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
        
        
        
        if (Auth::user()->role_id != config('constant.roles.Haulers')) {
            $farm = CustomerFarm::find($createJobRequest->farm_id);
            if (!$farm->isOwner()) {
                return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.',
            ], 421);
            }
        }

        DB::beginTransaction();
        try {
            $data = [
                'job_created_by' => $createJobRequest->user()->id,
                'card_id' => null,
                'service_id' => $createJobRequest->service_id,
                'gate_no' => (isset($createJobRequest->gate_no) && $createJobRequest->gate_no != '' && $createJobRequest->gate_no != null) ? $createJobRequest->gate_no : null,
                'time_slots_id' => (isset($createJobRequest->time_slots_id) && $createJobRequest->time_slots_id != '' && $createJobRequest->time_slots_id != null) ? $createJobRequest->time_slots_id : null,
                'job_providing_date' => $createJobRequest->job_providing_date,
                'weight' => (isset($createJobRequest->weight) && $createJobRequest->weight != '' && $createJobRequest->weight != null) ? $createJobRequest->weight : null,
                'is_repeating_job' => ($createJobRequest->is_repeating_job) ? 2 : 1,
                'repeating_days' => (isset($createJobRequest->repeating_days) && $createJobRequest->repeating_days != '' && $createJobRequest->repeating_days != null) ? json_encode(explode(',', $createJobRequest->repeating_days)) : null,
                'payment_mode' => (isset($createJobRequest->payment_mode) && $createJobRequest->payment_mode != '' && $createJobRequest->payment_mode != null) ? $createJobRequest->payment_mode : 3,
                'images' => null,
                'notes' => (isset($createJobRequest->notes) && $createJobRequest->notes != '' && $createJobRequest->notes != null) ? $createJobRequest->notes : null,
                'amount' => $createJobRequest->amount,
            ];
            
            if (Auth::user()->role_id != config('constant.roles.Haulers')) {
                $data['farm_id'] = (isset($createJobRequest->farm_id) && $createJobRequest->farm_id != '' && $createJobRequest->farm_id != null) ? $createJobRequest->farm_id : null;
                $data['customer_id'] = $farm->customer_id;
                $data['manager_id'] = (isset($createJobRequest->manager_id) && $createJobRequest->manager_id != '' && $createJobRequest->manager_id != null) ? $createJobRequest->manager_id : $farm->primary_manager->id;
            } else {
                $data['farm_id'] = null;
                $data['customer_id'] = Auth::user()->id;
                $data['manager_id'] = (isset($createJobRequest->manager_id) && $createJobRequest->manager_id != '' && $createJobRequest->manager_id != null) ? $createJobRequest->manager_id : null;
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

    /**
     * get farms
     */
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
            'message' => 'Job List',
            'data' => $customerFarm->jobs
        ], 200);
    }
    /**
     * get service time slots
     */
    public function getServiceSlots(Request $request)
    {
        $service = Service::whereId($request->service_id)->first();
        if ($service != null) {
            $timeSlots = TimeSlots::whereIn('id', json_decode($service->slot_time))->get();
            return response()->json([
                'status' => true,
                'message' => 'Service Slot Details.',
                'data' => $timeSlots
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'No time slot available.',
                'data' => []
            ], 500);
        }
    }
    /**
     * @method get: Function to get single jobs.
     *
     * @param Job $job : Job which need to be fetched.
     */
    public function get(Job $job)
    {

        return response()->json([
            'status' => true,
            'message' => 'Job Details.',
            'data' => $job->where('id', $job->id)->with('farm','manager', 'service')->first()
        ], 200);
    }

    /**
     * @method cancelJob: Function to cancel a job. A job can not be canceled after 24 hours.
     *
     * @param Job $job : Job which need to be canceled.
     */
    public function cancelJob(Job $job)
    {
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

    /**
     * @method upcomingJobs: Function to get upcoming jobs.
     *
     * @param CustomerFarm $customerFarm : Farm whose jobs need to be fetched.
     */
    public function upcomingJobs(CustomerFarm $customerFarm)
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
            'message' => 'Customer Details',
            'now' => Carbon::now()->format('Y-m-d'),
            'data' => Job::Where('farm_id', $customerFarm->id)->where('job_providing_date', '>', Carbon::now())->with('truck_driver', 'truck')->get()
        ], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'job_id' => 'required',
                    'manager_id' => 'required',
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
        
        $checkIfEdittingAllowed = Job::where('id', $request->job_id)->first();
        if ($checkIfEdittingAllowed->job_status == config('constant.job_status.open')) {
            try {
                Job::whereId($request->job_id)->update([
                    'manager_id' => (isset($request->manager_id) && $request->manager_id != '' && $request->manager_id != null) ? $request->manager_id : null,
                    'farm_id' => (isset($request->farm_id) && $request->farm_id != '' && $request->farm_id != null) ? $request->farm_id : null,
                    'gate_no' => (isset($request->gate_no) && $request->gate_no != '' && $request->gate_no != null) ? $request->gate_no : null,
                    'service_id' => $request->service_id,
                    'time_slots_id' => (isset($request->time_slots_id) && $request->time_slots_id != '' && $request->time_slots_id != null) ? $request->time_slots_id : null,
                    'job_providing_date' => $request->job_providing_date,
                    'weight' => (isset($request->weight) && $request->weight != '' && $request->weight != null) ? $request->weight : null,
                    'is_repeating_job' => $request->is_repeating_job,
                    'repeating_days' => (isset($request->repeating_days) && $request->repeating_days != '' && $request->repeating_days != null) ? json_encode(explode(',', $request->repeating_days)) : null,
                    'payment_mode' => (isset($request->payment_mode) && $request->payment_mode != '' && $request->payment_mode != null) ? $request->payment_mode : 3,
                    'images' => (isset($request->images) && $request->images != '' && $request->images != null) ? $request->images : null,
                    'notes' => (isset($request->notes) && $request->notes != '' && $request->notes != null) ? $request->notes : null,
                    'amount' => $request->amount,
                ]);
                $mailData = [
                    'job_id' => $request->job_id,
                    'customer_id' => $checkIfEdittingAllowed->customer_id,
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
                    'message' => 'You cannot cancel the job.',
                    'data' => []
                ], 500);
    }

    /**
     * @method myJobs: Functin to get jobs.
     */
    public function myJobs()
    {
        return response()->json([
            'status' => true,
            'message' => 'Job List',
            'data' => Auth::user()->myJobs()
        ], 200);
    }

     /**
     * @method myUpcomingJobs: FunctiOn to get upcoming jobs.
     */
    public function myUpcomingJobs()
    {
        return response()->json([
            'status' => true,
            'message' => 'Job List',
            'data' => Auth::user()->myUpcomingJobs()
        ], 200);
    }
}
