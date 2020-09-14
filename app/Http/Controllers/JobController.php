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
use App\Job;
use App\CustomerFarm;

class JobController extends Controller {
  
    /**
     * create job
     */
    public function createJob(Request $request) {
        $validator = Validator::make($request->all(), [
                    'customer_id' => 'required',
                    'service_id' => 'required',
                    'job_providing_date' => 'required',
                    'is_repeating_job' => 'required',
                    'payment_mode' => 'required',
                    'amount' => 'required',
                    'repeating_days' => 'required_if:is_repeating_job,==,2',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $checkService = Service::where('id', $request->service_id)->first();
        if ($checkService->service_for == config('constant.roles.Customer')) {
            if ((isset($request->manager_id) && $request->manager_id == null && $request->manager_id == '') || (isset($request->farm_id) && $request->farm_id == null && $request->farm_id == '') || (isset($request->time_slots_id) && $request->time_slots_id == null && $request->time_slots_id == '')) {
                return response()->json([
                            'status' => false,
                            'message' => 'The given data was invalid.',
                            'data' => []
                                ], 422);
            }
        }
        try {
            $job = new Job([
                'job_created_by' => $request->user()->id,
                'customer_id' => $request->customer_id,
                'manager_id' => (isset($request->manager_id) && $request->manager_id != '' && $request->manager_id != null) ? $request->manager_id : null,
                'farm_id' => (isset($request->farm_id) && $request->farm_id != '' && $request->farm_id != null) ? $request->farm_id : null,
                'service_id' => $request->service_id,
                'gate_no' => (isset($request->gate_no) && $request->gate_no != '' && $request->gate_no != null) ? $request->gate_no : null,
                'time_slots_id' => (isset($request->time_slots_id) && $request->time_slots_id != '' && $request->time_slots_id != null) ? $request->time_slots_id : null,
                'job_providing_date' => $request->job_providing_date,
                'weight' => (isset($request->weight) && $request->weight != '' && $request->weight != null) ? $request->weight : null,
                'is_repeating_job' => $request->is_repeating_job,
                'repeating_days' => (isset($request->repeating_days) && $request->repeating_days != '' && $request->repeating_days != null) ? $request->repeating_days : null,
                'payment_mode' => $request->payment_mode,
                'images' => (isset($request->images) && $request->images != '' && $request->images != null) ? $request->images : null,
                'notes' => (isset($request->notes) && $request->notes != '' && $request->notes != null) ? $request->notes : null,
                'amount' => $request->amount,
            ]);
            if ($job->save()) {
                $mailData = [
                    'job_id' => $job->id,
                    'customer_id' => $request->customer_id,
                    'manager_id' => isset($request->manager_id) ? $request->manager_id : null
                ];
                $this->_sendPaymentEmail($mailData);
                return response()->json([
                            'status' => true,
                            'message' => 'Job created successfully.',
                            'data' => []
                                ], 200);
            }
        } catch (\Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }

    /**
     * get customers and hauler
     */
    public function getCustomers() {
        return response()->json([
                    'status' => true,
                    'message' => 'Customers Details',
                    'data' => User::whereRoleId(config('constant.roles.Customer'))
                            ->orWhere('role_id', config('constant.roles.Haulers'))
                            ->get()
                        ], 200);
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
