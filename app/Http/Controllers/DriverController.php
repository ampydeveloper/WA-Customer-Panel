<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
                    'driver_first_name' => 'required',
                    'driver_last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'driver_phone' => 'required',
                    'driver_address' => 'required',
                    'driver_city' => 'required',
                    'driver_province' => 'required',
                    'driver_zipcode' => 'required',
                    'driver_licence' => 'required|unique:drivers',
                    'driver_licence_image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        
        try {
            $newPassword = Str::random();
            $user = new User([
                'prefix' => (isset($request->driver_prefix) && $request->driver_prefix != '' && $request->driver_prefix != null) ? $request->driver_prefix : null,
                'first_name' => $request->driver_first_name,
                'last_name' => $request->driver_last_name,
                'email' => $request->email,
                'phone' => $request->driver_phone,
                'address' => $request->driver_address,
                'city' => $request->driver_city,
                'state' => $request->driver_province,
                'zip_code' => $request->driver_zipcode,
                'user_image' => (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null,
                'role_id' => config('constant.roles.Hauler_driver'),
                'created_from_id' => $request->user()->id,
                'created_by' => $request->user()->id,
                'hauler_driver_licence' => $request->driver_licence,
                'hauler_driver_licence_image' => $request->driver_licence_image,
                'is_confirmed' => 1,
                'is_active' => 1,
                'password' => bcrypt($newPassword)
            ]);
            if ($user->save()) {
                $this->_confirmPassword($user, $newPassword);
                return response()->json([
                                'status' => true,
                                'message' => 'Driver created successfully.',
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
    
    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
                    'driver_first_name' => 'required',
                    'driver_last_name' => 'required',
                    'email' => 'required',
                    'driver_phone' => 'required',
                    'driver_address' => 'required',
                    'driver_city' => 'required',
                    'driver_province' => 'required',
                    'driver_zipcode' => 'required',
                    'driver_licence' => 'required',
                    'driver_licence_image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $driver = User::whereId($request->driver_id)->first();
        if ($request->email != '' && $request->email != null) {
            if ($driver->email !== $request->email) {
                $checkEmail = User::where('email', $request->email)->first();
                if ($checkEmail !== null) {
                    if ($checkEmail->id !== $driver->id) {
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
        $checkDriverLicence = User::where('driver_licence', $request->driver_licence)->first();
        if ($checkDriverLicence !== null) {
            if ($checkDriverLicence->id !== $driver->id) {
                return response()->json([
                            'status' => false,
                            'message' => 'Driver lecience is already taken.',
                            'data' => []
                                ], 422);
            }
        }
        try {
            DB::beginTransaction();
            if ($request->password != '' && $request->password != null) {
                $driver->password = bcrypt($request->password);
            }
            $driver->prefix = (isset($request->driver_prefix) && $request->driver_prefix != '' && $request->driver_prefix != null) ? $request->driver_prefix : null;
            $driver->first_name = $request->driver_first_name;
            $driver->last_name = $request->driver_last_name;
            $driver->email = $request->email;
            $driver->phone = $request->driver_phone;
            $driver->address = $request->driver_address;
            $driver->city = $request->driver_city;
            $driver->state = $request->driver_province;
            $driver->zip_code = $request->driver_zipcode;
            $driver->user_image = (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null;
            $driver->hauler_driver_licence = $request->driver_licence;
            $driver->hauler_driver_licence_image = $request->driver_licence_image;
            if(isset($confirmed)) {
                $driver->is_confirmed = $confirmed;
            }
            if ($driver->save()) {
                DB::commit();
                if (isset($confirmed)) {
                    $this->_updateEmail($driver, $request->email);
                }
                return response()->json([
                            'status' => true,
                            'message' => 'Driver details updated successfully.',
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
    public function get(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Hauler details',
                    'data' => user::whereId($request->driver_id)->first()
                        ], 200);
        
    }
    public function delete(Request $request) {
        try {
            User::whereId($request->driver_id)->delete();
            return response()->json([
                        'status' => true,
                        'message' => 'Hauler driver deleted successfully.',
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
