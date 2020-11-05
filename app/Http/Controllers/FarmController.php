<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
//use App\Service;
//use Carbon\Carbon;
//use App\TimeSlots;
//use App\Models\Job;
use App\Models\User;
//use App\Models\Payment;
//use App\ServicesTimeSlot;
use Illuminate\Support\Str;
use App\Models\CustomerFarm;
use Illuminate\Http\Request;
//use App\Models\ManagerDetail;
//use Illuminate\Validation\Rule;
//use App\Models\CustomerCardDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Farm\{
    CreateFarmRequest,
    UpdateFarmRequest
};
use App\Http\Requests\Farm\Manager\{
    CreateFarmManagerRequest
};
class FarmController extends Controller
{
    
    public function create(CreateFarmRequest $request)
    {
        $customer = $request->user();
        if($customer->role_id != config('constant.roles.Customer')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.',
                'data' => []
            ], 421);
        }
        try {
            DB::beginTransaction();
            $farmDetails = new CustomerFarm([
                'customer_id' => $customer->id,
                'farm_address' => $request->farm_address,
                'farm_unit' => (isset($request->farm_unit) && $request->farm_unit != '' && $request->farm_unit != null) ? ($request->farm_unit) : null,
                'farm_city' => $request->farm_city,
                'farm_province' => $request->farm_province,
                'farm_zipcode' => $request->farm_zipcode,
                'farm_active' => 1,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'created_by' => $customer->id,
                'distance' => $this->getDistance($request->latitude, $request->longitude, null, null, 'M')
            ]);
            if ($request->farm_image && count($request->farm_image) > 0) {  
                    $farmImages = [];
                foreach ($request->farm_image as $image) {
                    $imageName = $farmDetails->putImage($image);
                    if ($imageName) {
                        $farmImages[] = $imageName;
                    }
                }
                $farmDetails['farm_image'] = json_encode($farmImages);
            }

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
                        'role_id' => config('constant.roles.Customer_Manager'),
                        'created_from_id' => $customer->id,
                        'is_confirmed' => 1,
                        'is_active' => 1,
                        'created_by' => $customer->id,
                        'farm_id' => $farmDetails->id,
                        'password' => bcrypt($newPassword)
                    ]);
                    if ($manager['manager_image']) {
                        $imageName = $saveManger->putImage($manager['manager_image']);
                        $saveManger['user_image'] = json_encode($imageName);
                    }
                    if ($saveManger->save()) {
                        $this->_confirmPassword($saveManger, $newPassword);
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
    

    public function getDistance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $lat2 = ($lat2 == null) ? config('constant.warehouse.lat') : $lat2;
        $lon2 = ($lon2 == null) ? config('constant.warehouse.lon') : $lon2;
        $lat1 = (float)$lat1;
        $lat2 = (float)$lat2;
        $lon1 = (float)$lon1;
        $lon2 = (float)$lon2;

        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
          }
          else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
        
            if ($unit == "K") {
              return (int) ($miles * 1.609344);
            } else if ($unit == "N") {
              return (int) ($miles * 0.8684);
            } else {
              return (int) $miles;
            }
          }
    }
    
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
                'message' => 'Unauthorized access.',
                'data' => []
            ], 421);
    }
    

    public function update(CustomerFarm $customerFarm, UpdateFarmRequest $request)
    {
        $user = request()->user();
        if ($user->canAccessFarm($customerFarm)) {
            try {
                $customerFarm->update([
                    'farm_address' => $request->farm_address,
                    'farm_unit' => (isset($request->farm_unit) && $request->farm_unit != '' && $request->farm_unit != null) ? ($request->farm_unit) : null,
                    'farm_city' => $request->farm_city,
                    'farm_province' => $request->farm_province,
                    'farm_zipcode' => $request->farm_zipcode,
                    'farm_active' => $request->farm_active,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'distance' => $this->getDistance($request->latitude, $request->longitude, null, null, 'M')
                ]);
                if ($request->farm_image && count($request->farm_image) > 0) {
                    $farmImages = [];
                    foreach ($request->farm_image as $image) {
                        $imageName = $customerFarm->putImage($image);
                        if ($imageName) {
                            $farmImages[] = $imageName;
                        }
                    }
                    $customerFarm->update(['farm_image' => json_encode($farmImages)]);
                }

                foreach ($request->manager_details as $manager) {

                    $managerCheck = User::whereId($manager['id'])->first();
                    if ($manager['email'] != '' && $manager['email'] != null) {
                        if ($managerCheck->email !== $manager['email']) {
                            $checkEmail = User::where('email', $manager['email'])->first();
                            if ($checkEmail !== null) {
                                if ($checkEmail->id !== $managerCheck->id) {
                                    return response()->json([
                                                'status' => false,
                                                'message' => 'Email is already taken.',
                                                'data' => []
                                                    ], 422);
                                }
                            }
                            $confirmed = 0;
                        }
                    }
                    $data = [
                        'prefix' => (isset($manager['manager_prefix']) && $manager['manager_prefix'] != '' && $manager['manager_prefix'] != null) ? $manager['manager_prefix'] : null,
                        'first_name' => $manager['manager_first_name'],
                        'last_name' => $manager['manager_last_name'],
                        'email' => $manager['email'],
                        'phone' => $manager['manager_phone'],
                        'address' => $manager['manager_address'],
                        'city' => $manager['manager_city'],
                        'state' => $manager['manager_province'],
                        'zip_code' => $manager['manager_zipcode'],
                        'farm_id' => $customerFarm->id,
                        'is_active' => $manager['is_active']
                    ];
                    if (isset($confirmed)) {
                        $data['is_confirmed'] = $confirmed;
                    }

                    if ($manager['manager_image']) {
                        $imageName = $request->user()->putImage($manager['manager_image']);
                        $data['user_image'] = json_encode($imageName);
                    }
                    if (User::where('id', $manager['id'])->update($data)) {
                        if (isset($confirmed)) {
                            $this->_updateEmail($manager, $request->email);
                        }
                    }
                }

                return response()->json([
                            'status' => true,
                            'message' => 'Farm details updated successfully.',
                            'farm' => $customerFarm
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
                'message' => 'Unauthorized access.',
                'data' => []
            ], 421);
    }
    
    public function deleteFarm(CustomerFarm $customerFarm)
    {
        if (!$customerFarm->isOwner()) {
            return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                ], 421);
        }
        try {
            DB::beginTransaction();
            $customerFarm->managers()->delete();
            $customerFarm->delete();
            DB::commit();
            
            return response()->json([
                'status' => false,
                'message' => 'Farm deleted successfully.',
            ], 200);
        } catch(\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Unable to delete farm try again later.',
            ], 423);
        }
    }
    
    public function getFarmManagers(CustomerFarm $customer_farm)
    {
        if(Auth::user()->role_id == config('constant.roles.Customer') || Auth::user()->role_id == config('constant.roles.Customer_Manager')) {
            return response()->json([
                'status' => true,
                'message' => 'Farm manager details.',
                'data' => $customer_farm->managers
            ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                    'data' => []
                ], 421);
    }

    /*
     * @method createFarmManager : Function to create farm managers.
     * 
     */
    public function createFarmManager(CustomerFarm $customerFarm, CreateFarmManagerRequest $request)
    {
        if (Auth::user()->role_id == config('constant.roles.Customer') || Auth::user()->role_id == config('constant.roles.Customer_Manager')) {
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
                    'role_id' => config('constant.roles.Customer_Manager'),
                    'created_from_id' => Auth::user()->id,
                    'is_confirmed' => 1,
                    'is_active' => 1,
                    'created_by' => Auth::user()->id,
                    'farm_id' => $customerFarm->id,
                    'password' => bcrypt($newPassword)
                ]);
                if ($request->manager_image) {
                    $imageName = $saveManager->putImage($request->manager_image);
                    $saveManager['user_image'] = json_encode($imageName);
                }
                if ($saveManager->save()) {
                    $this->_confirmPassword($saveManager, $newPassword);
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Manager created successfully.',
                                'data' => []
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                        'data' => []
                            ], 421);
        }
    }
    
    public function getFarmManager(CustomerFarm $customerFarm,User $manager) {
        if(Auth::user()->role_id == config('constant.roles.Customer') || Auth::user()->role_id == config('constant.roles.Customer_Manager')) {
            return response()->json([
                'status' => true,
                'message' => 'Farm manager details.',
                'data' => $manager
            ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                    'data' => []
                ], 421);
    }

    public function updateFarmManager(CustomerFarm $customerFarm, Request $request, User $manager)
    {
        $validator = Validator::make($request->all(), [
                    'manager_first_name' => 'required',
                    'manager_last_name' => 'required',
                    'email' => 'required|email',
                    'manager_phone' => 'required',
                    'manager_address' => 'required',
                    'manager_city' => 'required',
                    'manager_province' => 'required',
                    'manager_zipcode' => 'required',
                    'manager_is_active' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        if(Auth::user()->role_id == config('constant.roles.Customer') || Auth::user()->role_id == config('constant.roles.Customer_Manager')) {

            try {
                DB::beginTransaction();
                if ($request->email != '' && $request->email != null) {
                    if ($manager->email !== $request->email) {
                        $checkEmail = User::where('email', $request->email)->first();
                        if ($checkEmail !== null) {
                            return response()->json([
                                        'status' => false,
                                        'message' => 'Email is already taken.',
                                        'data' => []
                                            ], 422);
                        }
                        $confirmed = 0;
                    }
                }
                $data = [
                    'prefix' => (isset($request->manager_prefix) && $request->manager_prefix != '' && $request->manager_prefix != null) ? $request->manager_prefix : null,
                    'first_name' => $request->manager_first_name,
                    'last_name' => $request->manager_last_name,
                    'email' => $request->email,
                    'phone' => $request->manager_phone,
                    'address' => $request->manager_address,
                    'city' => $request->manager_city,
                    'state' => $request->manager_province,
                    'zip_code' => $request->manager_zipcode,
                    'farm_id' => $customerFarm->id,
                    'is_active' => $request->manager_is_active,
                ];
                if (isset($confirmed)) {
                    $data['is_confirmed'] = $confirmed;
                }

                if ($request->manager_image) {
                    $imageName = $manager->putImage($request->manager_image);
                    $data['user_image'] = json_encode($imageName);
                }
                if (User::where('id', $manager->id)->update($data)) {
                    DB::commit();
                    if (isset($confirmed)) {
                        $this->_updateEmail($manager, $request->email);
                    }
                    return response()->json([
                                'status' => true,
                                'message' => 'Manager updated successfully.',
                                'data' => []
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
        return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.',
                'data' => []
            ], 421);
    }

    public function deleteFarmManager(CustomerFarm $customerFarm, User $user)
    {
        $farm = $user->managerOf;
        if (!$farm || !$farm->isOwner() || $customerFarm->id != $farm->id) {
            return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                ], 421);
        }

        try {
            // There is no need to delete data from manager details table.
            $user->delete();
            return response()->json([
                'status' => false,
                'message' => 'Manager deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to delete manager try again later.',
            ], 423);   
        }
    }
    
    public function changeManager(CustomerFarm $customerFarm, User $user) {
        if($customerFarm->isOwner()) {
            try {
                $user->update(['farm_id' => $customerFarm->id]);
                return response()->json([
                            'status' => false,
                            'message' => 'Manager farm changed successfully.',
                                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                            'status' => false,
                            'message' => 'Unable to change manager farm. Try again later.',
                                ], 423);
            }
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                ], 421);
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
            'verificationLink' => env('APP_URL') . '/confirm-update-email/' . base64_encode($email) . '/' . base64_encode($user->id)
        );

        Mail::send('email_templates.welcome_email', $data, function ($message) use ($name, $email) {
            $message->to($email, $name)->subject('Email Address Confirmation');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
    }
    
    /**
     * @method isUniqueManager: Function to check if email address is unique or not
     * 
     */
//    public function isUniqueManager(string $email)
//    {
////        dd($email);
//        $validator = Validator::make(['email' => $email], [
//            'email' => 'required|email|unique:users',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                        'status' => false,
//                        'message' => 'The given data was invalid.',
//                        'data' => $validator->errors()
//                    ], 422);
//        }
//        return response()->json([
//            'status' => true,
//        ], 200);
//    }
   
}
