<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mail;
use App\Service;
use App\TimeSlots;
use App\ServicesTimeSlot;
use App\Models\User;
use App\Models\ManagerDetail;
use App\Models\CustomerFarm;
use App\Models\CustomerCardDetail;
use App\Models\Payment;
use App\Models\Job;
use Carbon\Carbon;
use App\Http\Requests\Farm\{
    CreateFarmRequest
};

class FarmController extends Controller
{
    /**
     * @method create: Function to create customer farm.
     * 
     * @return JSON response.
     */
    public function create(CreateFarmRequest $request)
    {
        try {
            $customer = $request->user();
            DB::beginTransaction();
            $farmDetails = new CustomerFarm([
                'customer_id' => $customer->id,
                'farm_address' => $request->farm_address,
                'farm_unit' => (isset($request->farm_unit) && $request->farm_unit != '' && $request->farm_unit != null) ? ($request->farm_unit) : null,
                'farm_city' => $request->farm_city,
                'farm_province' => $request->farm_province,
                'farm_zipcode' => $request->farm_zipcode,
                'farm_image' => (isset($request->farm_images) && $request->farm_images != '' && $request->farm_images != null) ? json_encode($request->farm_images) : null,
                'farm_active' => $request->farm_active,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'created_by' => $request->user()->id,
            ]);
            if ($farmDetails->save()) {
                foreach ($request->manager_details as $manager) {
                    $newPassword = Str::random();
                    $saveManger = new User([
                        'prefix' => (isset($manager['manager_prefix']) && $manager['manager_prefix'] != '' && $manager['manager_prefix'] != null) ? $manager['manager_prefix'] : null,
                        'first_name' => $manager['manager_first_name'],
                        'last_name' => $manager['manager_last_name'],
                        'email' => $manager['email'],
                        'phone' => $manager['manager_phone'],
                        'address' => $manager['manager_address'],
                        'city' => $manager['manager_city'],
                        'state' => $manager['manager_province'],
                        'zip_code' => $manager['manager_zipcode'],
                        'user_image' => (isset($manager['manager_image']) && $manager['manager_image'] != '' && $manager['manager_image'] != null) ? $manager['manager_image'] : null,
                        'role_id' => config('constant.roles.Customer_Manager'),
                        'created_from_id' => $request->user()->id,
                        'is_confirmed' => 1,
                        'is_active' => 1,
                        'created_by' => $request->customer_id,
                        'farm_id' => $farmDetails->id,
                        'password' => bcrypt($newPassword)
                    ]);

                    if ($saveManger->save()) {
                        $mangerDetails = new ManagerDetail([
                            'user_id' => $saveManger->id,
                            'identification_number' => $manager['manager_id_card'],
                            'document' => $manager['manager_card_image'],
                            'salary' => $manager['salary'],
                            'joining_date' => date('Y/m/d'),
                        ]);
                        if ($mangerDetails->save()) {
                            $this->_confirmPassword($saveManger, $newPassword);
                        }
                    }
                }
                DB::commit();
                return response()->json([
                            'status' => true,
                            'message' => 'Customer farm created successfully.',
                            'data' => $farmDetails
                        ], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                    ], 500);
        }
    }

    /**
     * create customer manager
     */
    public function createCustomerManager(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'customer_id' => 'required',
                    'farm_id' => 'required',
                    'manager_first_name' => 'required',
                    'manager_last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'manager_phone' => 'required',
                    'manager_address' => 'required',
                    'manager_city' => 'required',
                    'manager_province' => 'required',
                    'manager_zipcode' => 'required',
                    'manager_card_image' => 'required',
                    'manager_id_card' => 'required',
                    'salary' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        try {
            DB::beginTransaction();
            $newPassword = Str::random();
            $saveManager = new User([
                'prefix' => (isset($request->manager_prefix) && $request->manager_prefix != '' && $request->manager_prefix != null) ? $request->manager_prefix : null,
                'first_name' => $request->manager_first_name,
                'last_name' => $request->manager_last_name,
                'email' => $request->email,
                'phone' => $request->manager_phone,
                'address' => $request->manager_address,
                'city' => $request->manager_city,
                'state' => $request->manager_province,
                'zip_code' => $request->manager_zipcode,
                'user_image' => (isset($request->manager_image) && $request->manager_image != '' && $request->manager_image != null) ? $request->manager_image : null,
                'role_id' => config('constant.roles.Customer_Manager'),
                'created_from_id' => $request->user()->id,
                'is_confirmed' => 1,
                'is_active' => 1,
                'created_by' => $request->customer_id,
                'farm_id' => $request->farm_id,
                'password' => bcrypt($newPassword)
            ]);

            if ($saveManager->save()) {
                $managerDetails = new ManagerDetail([
                    'user_id' => $saveManager->id,
                    'identification_number' => $request->manager_id_card,
                    'document' => $request->manager_card_image,
                    'salary' => $request->salary,
                    'joining_date' => date('Y/m/d'),
                ]);
                if ($managerDetails->save()) {
                    $this->_confirmPassword($saveManager, $newPassword);
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Customer created successfully.',
                                'data' => []
                                    ], 200);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }


    /**
     * @method get : Function to get farm.
     * 
     */
    public function get(CustomerFarm $customer_farm)
    {
        $user = request()->user();
        if ($user->canAccessFarm($customer_farm)) {
            return response()->json([
                'status' => true,
                'message' => 'Customer farms details',
                'data' => $customer_farm
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Farm not found.',
            'data' => []
        ], 200);
    }

    /**
     * get manager details
     */
    public function getCustomerManager(Request $request)
    {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer manager details',
                    'data' => [
                        'managerDetails' => User::where('created_by', $request->customer_id)->get(),
                        'farm' => CustomerFarm::where('customer_id', $request->customer_id)->get()
                    ]
                        ], 200);
    }

    public function getFarmManager(Request $request)
    {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer manager details',
                    'data' => CustomerFarm::where('id', $request->farm_id)->with('farmManager')->get()
                        ], 200);
    }
   
    /*
     * update farm details
     */
    public function updateFarm(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                        'farm_id' => 'required',
                        'farm_address' => 'required',
                        'farm_city' => 'required',
                        'farm_province' => 'required',
                        'farm_zipcode' => 'required',
                        'farm_active' => 'required',
                        'latitude' => 'required',
                        'longitude' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                            'status' => false,
                            'message' => 'The given data was invalid.',
                            'data' => $validator->errors()
                                ], 422);
            }
            CustomerFarm::whereId($request->farm_id)->update([
                'farm_address' => $request->farm_address,
                'farm_unit' => (isset($request->farm_unit) && $request->farm_unit != '' && $request->farm_unit != null) ? ($request->farm_unit) : null,
                'farm_city' => $request->farm_city,
                'farm_province' => $request->farm_province,
                'farm_zipcode' => $request->farm_zipcode,
                'farm_image' => (isset($request->farm_images) && $request->farm_images != '' && $request->farm_images != null) ? json_encode($request->farm_images) : null,
                'farm_active' => $request->farm_active,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return response()->json([
                        'status' => true,
                        'message' => 'Farm details updated successfully.',
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

    public function updateManager(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'manager_id' => 'required',
                    'farm_id' => 'required',
                    'manager_first_name' => 'required',
                    'manager_last_name' => 'required',
                    'manager_phone' => 'required',
                    'manager_address' => 'required',
                    'manager_city' => 'required',
                    'manager_province' => 'required',
                    'manager_zipcode' => 'required',
                    'manager_is_active' => 'required',
                    'manager_card_image' => 'required',
                    'manager_id_card' => 'required',
                    'salary' => 'required',
                    'joining_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $confirmed = 1;
        $manager = User::whereId($request->manager_id)->first();
        if ($request->email != '' && $request->email != null) {
            $checkEmail = User::where('email', $request->email)->first();
            if ($checkEmail !== null) {
                if ($checkEmail->id !== $manager->id) {
                    return response()->json([
                                'status' => false,
                                'message' => 'Email is already taken.',
                                'data' => []
                                    ], 422);
                }
            }
            if ($manager->email !== $request->email) {
                $confirmed = 0;
            }
        }
        try {
            DB::beginTransaction();
            if ($request->password != '' && $request->password != null) {
                $manager->password = bcrypt($request->password);
            }
            $manager->prefix = (isset($request->manager_prefix) && $request->manager_prefix != '' && $request->manager_prefix != null) ? $request->manager_prefix : null;
            $manager->first_name = $request->manager_first_name;
            $manager->last_name = $request->manager_last_name;
            $manager->email = $request->email;
            $manager->phone = $request->manager_phone;
            $manager->address = $request->manager_address;
            $manager->city = $request->manager_city;
            $manager->state = $request->manager_province;
            $manager->zip_code = $request->manager_zipcode;
            $manager->user_image = (isset($request->manager_image) && $request->manager_image != '' && $request->manager_image != null) ? $request->manager_image : null;
            $manager->is_active = $request->manager_is_active;
            $manager->farm_id = $request->farm_id;
            if ($manager->save()) {
                $managerDetail = ManagerDetail::where('user_id', $request->manager_id)->first();
                $managerDetail->salary = $request->salary;
                $managerDetail->identification_number = $request->manager_id_card;
                $managerDetail->joining_date = $request->joining_date;
                $managerDetail->releaving_date = isset($request->releaving_date) ? $request->releaving_date : null;
                $managerDetail->document = $request->manager_card_image;
                if ($managerDetail->save()) {
                    DB::commit();
                    if ($confirmed == 0) {
                        $this->_updateEmail($manager, $request->email);
                    }
                    return response()->json([
                                'status' => true,
                                'message' => 'Manager updated successfully.',
                                'data' => []
                                    ], 200);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    /**
     * email for new registration and password
     */
    public function _confirmPassword($user, $newPassword)
    {
        $name = $user->first_name . ' ' . $user->last_name;
        $data = array(
            'user' => $user,
            'password' => $newPassword
        );

        Mail::send('email_templates.welcome_email_manager', $data, function ($message) use ($user, $name) {
            $message->to($user->email, $name)->subject('Login Details');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
    }
    
    public function _updateEmail($user, $email)
    {
        $name = $user->first_name . ' ' . $user->last_name;
        $data = array(
            'name' => $name,
            'email' => $email,
            'verificationLink' => env('APP_URL') . 'confirm-update-email/' . base64_encode($email) . '/' . base64_encode($user->id)
        );

        Mail::send('email_templates.welcome_email', $data, function ($message) use ($name, $email) {
            $message->to($email, $name)->subject('Email Address Confirmation');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
    }
}