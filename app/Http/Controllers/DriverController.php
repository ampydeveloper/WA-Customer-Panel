<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use Validator;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerActivity;

class DriverController extends Controller
{
    public function allDriversList() {
        if(Auth::user()->role_id == config('constant.roles.Haulers')) {
            return response()->json([
                'status' => true,
                'message' => 'Hauler Driver details',
                'data' => User::where('role_id', config('constant.roles.Hauler_driver'))->where('created_by', Auth::user()->id)->get()
            ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                ], 421);
    }
    
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
                    'driver_first_name' => 'required',
                    'driver_last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'driver_phone' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        if (Auth::user()->role_id == config('constant.roles.Haulers')) {
            try {
                $newPassword = Str::random();
                DB::beginTransaction();
                $user = new User([
                    'prefix' => (isset($request->prefix) && $request->prefix != '' && $request->prefix != null) ? $request->prefix : null,
                    'first_name' => $request->driver_first_name,
                    'last_name' => $request->driver_last_name,
                    'email' => $request->email,
                    'phone' => $request->driver_phone,
                    'role_id' => config('constant.roles.Hauler_driver'),
                    'created_from_id' => $request->user()->id,
                    'created_by' => $request->user()->id,
                    'is_confirmed' => 1,
                    'is_active' => 1,
                    'password' => bcrypt($newPassword)
                ]);
                if ($user->save()) {
                    if ($request->driver_image) {
                        $imageName = $user->putImage($request->driver_image);
                        $user->user_image = $imageName;
                        if ($user->save()) {
                            $this->_confirmPassword($user, $newPassword);
                            
                            // Notification is required.
                            $customerActivity = new CustomerActivity([
                                'customer_id' => $request->user()->id,
                                'created_by' => $request->user()->id,
                                'activities' => 'Hauler created ' . $user->first_name . ' as driver.',
                            ]);
                            if ($customerActivity->save()) {
                                DB::commit();
                                return response()->json([
                                            'status' => true,
                                            'message' => 'Driver created successfully.',
                                            'data' => $user
                                                ], 200);
                            }
                        }
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
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                        ], 421);
    }

    public function update(Request $request, User $driver) {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'is_active' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        if(Auth::user()->role_id == config('constant.roles.Haulers')) {
            if ($request->email != '' && $request->email != null) {
                if ($driver->email !== $request->email) {
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
            try {
                DB::beginTransaction();
                $driver->prefix = (isset($request->prefix) && $request->prefix != '' && $request->prefix != null) ? $request->prefix : null;
                $driver->first_name = $request->first_name;
                $driver->last_name = $request->last_name;
                $driver->email = $request->email;
                $driver->phone = $request->phone;
                $driver->is_active = $request->is_active == false ? 0 : 1;
                if (isset($confirmed)) {
                    $driver->is_confirmed = $confirmed;
                }
                if ($request->driver_image) {
                    $imageName = $driver->putImage($request->driver_image);
                    $driver->user_image = $imageName;
                }
                if ($driver->save()) {
                    if (isset($confirmed)) {
                        $this->_updateEmail($driver, $request->email);
                    }
                    
                    $customerActivity = new CustomerActivity([
                        'customer_id' => Auth::user()->id,
                        'created_by' => Auth::user()->id,
                        'activities' => 'Hauler update '.$driver->first_name."'s details.",
                    ]);
                    if ($customerActivity->save()) {
                        
                        // Notification is required.
                        DB::commit();
                        return response()->json([
                                'status' => true,
                                'message' => 'Driver details updated successfully.',
                                'data' => $driver
                                    ], 200);
                    }
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
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                ], 421);
    }
    public function get(User $driver) {
        if(Auth::user()->role_id == config('constant.roles.Haulers')) {
            return response()->json([
                    'status' => true,
                    'message' => 'Hauler details',
                    'data' => $driver
                        ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                ], 421);
        
        
    }
    public function deleteDriver(User $driver) {
        if (Auth::user()->role_id == config('constant.roles.Haulers')) {
            try {
                DB::beginTransaction();
                if($driver->delete()) {
                    $customerActivity = new CustomerActivity([
                        'customer_id' => Auth::user()->id,
                        'created_by' => Auth::user()->id,
                        'activities' => 'Hauler deleted '. $driver->first_name.' driver.',
                    ]);
                    if ($customerActivity->save()) {
                        DB::commit();
                        
                        // Notification is required.
                        
                        return response()->json([
                            'status' => true,
                            'message' => 'Hauler driver deleted successfully.',
                            'data' => []
                                ], 200);
                    }
                }
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
                        ], 421);
    }

    public function _confirmPassword($user, $newPassword) {
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
    
    public function _updateEmail($user, $email) {
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
