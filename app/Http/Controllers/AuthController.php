<?php

namespace App\Http\Controllers;

use Mail;
use App\Models\User;
use Socialite;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\GuzzleException;
use App\Http\Requests\Auth\{
    SignUpRequest,
    LoginRequest,
    ForgotPasswordRequest,
    ChangePasswordRequest,
    UpdateProfileRequest
};

class AuthController extends Controller
{

    /**
     * @method signup: Function to register a customer.
     *
     * @param SignUpRequest $request: Contains SignUp data.
     *
     * @return JSON response.
     */
    public function signup(SignUpRequest $request)
    {
        if ($request->role_id == config('constant.roles.Customer') || $request->role_id == config('constant.roles.Haulers')) {
            try {
                $user = new User([
                    'prefix' => (isset($request->prefix) && $request->prefix != '' && $request->prefix != null) ? $request->prefix : null,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->province,
                    'zip_code' => $request->zipcode,
                    'user_image' => (isset($request->hauler_image) && $request->hauler_image != '' && $request->hauler_image != null) ? $request->hauler_image : null,
                    'role_id' => $request->role_id,
                    'is_active' => 1,
                    'password' => bcrypt($request->password),
                    'password_changed_at' => Carbon::now(),
                ]);
                if ($user->save()) {
                    $this->_welcomeEmail($user);
                    // $this->_saveUserToHubSpot($user);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Your account is successfully created. We have sent you an e-mail to confirm your account.',
                    'data' => []
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => json_encode($e->getMessage()),
                    'data' => []
                ], 500);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Please check the type of user.',
            'data' => []
        ], 500);
    }


    /**
     * social login
     */
    public function SocialSignup($provider, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }
        try {
            $user = Socialite::driver($provider)->stateless()->user();
            $checkIfExist = User::whereEmail($user->user['email'])->first();
            if ($checkIfExist == null) {
                if ($provider == config('constant.login_providers.google')) {
                    $user = new User([
                        'first_name' => $user->user['given_name'],
                        'last_name' => $user->user['family_name'],
                        'email' => $user->user['email'],
                        'role_id' => $request->role_id,
                        'is_active' => 1,
                        'is_confirmed' => 1,
                        'provider' => $provider,
                        'token' => $user->token,
                        'password_changed_at' => Carbon::now()
                    ]);
                } elseif ($provider == config('constant.login_providers.facebook')) {
                    $user = new User([
                        'first_name' => $user['name'],
                        'email' => $user['email'],
                        'role_id' => $request->role_id,
                        'is_active' => 1,
                        'is_confirmed' => 1,
                        'provider' => $provider,
                        'token' => $user->token,
                        'password_changed_at' => Carbon::now()
                    ]);
                }
                $user->save();
                $this->_welcomeEmail($user);
                $this->_saveUserToHubSpot($user);
            } else {
                if ($checkIfExist->provider == null) {
                    $checkIfExist->provider == $provider;
                    $checkIfExist->token == $user->token;
                }
                if ($checkIfExist->password_changed_at == null || $checkIfExist->password_changed_at == '') {
                    $checkIfExist->password_changed_at = Carbon::now();
                }
                $checkIfExist->save();
                $user = $checkIfExist;
            }
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            return response()->json([
                'status' => true,
                'message' => 'Login Successful',
                'data' => array(
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString(),
                    'user' => $user
                )
            ]);
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
     * @method _welcomeEmail: Function to send welcome email to customer.
     *
     * @user User $user: User Model instance.
     *
     * @return void
     */
    public function _welcomeEmail(User $user)
    {
        $name = $user->first_name . ' ' . $user->last_name;
        $data = array(
            'name' => $name,
            'email' => $user->email,
            'verificationLink' => env('APP_URL') . '/auth/confirm-email/' . base64_encode($user->email)
        );

        Mail::send('email_templates.welcome_email', $data, function ($message) use ($user, $name) {
            $message->to($user->email, $name)->subject('Email Confirmation');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
    }

    /**
     * save user to hubspot
     */
    public function _saveUserToHubSpot($request)
    {
        if ($request->role_id == config('constant.roles.Customer')) {
            $userType = 'Customer';
        } else {
            $userType = 'Hauler';
        }
        $arr = [
            'properties' => [
                [
                    'property' => 'firstname',
                    'value' => $request->first_name
                ],
                [
                    'property' => 'lastname',
                    'value' => $request->last_name
                ],
                [
                    'property' => 'email',
                    'value' => $request->email
                ],
                [
                    'property' => 'phone',
                    'value' => $request->phone
                ],
                [
                    'property' => 'address',
                    'value' => $request->address
                ],
                [
                    'property' => 'province',
                    'value' => $request->province
                ],
                [
                    'property' => 'zipcode',
                    'value' => $request->zipcode
                ],
                [
                    'property' => 'User type',
                    'value' => $userType
                ],
            ]
        ];
        $post_json = json_encode($arr);
        $endpoint = config('constant.hubspot.api_url') . env('HUBSPOT_API_KEY');
        $client = new Client();
        $res = $client->request('POST', $endpoint, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => $post_json
        ]);
        return true;
    }

    /**
     * @method login: Login user and create token.
     *
     * @param LoginRequest $request: Contains valid login data.
     *
     * @return JSON response.
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'These credentials do not match our records.',
                    'data' => []
                ], 401);
            }

            if ($user->is_confirmed == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is not confirmed. Please click the confirmation link in your e-mail box.',
                    'data' => []
                ], 401);
            }
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'These credentials do not match our records.',
                    'data' => []
                ], 401);
            }
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();

            return response()->json([
                'status' => true,
                'message' => 'Login Successful.',
                'data' => [
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => json_encode($e->getMessage()),
                'data' => []
            ], 500);
        }
    }

    /**
     * @method forgotPassword: Function to handle forgot password feature.
     *
     * @param ForgotPasswordRequest $request: Contains valid forgot password data.
     *
     * @return JSON response.
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $user = User::whereEmail($request->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not found!',
                    'data' => []
                ], 404);
            }
            $name = $user->first_name . ' ' . $user->last_name;
            $data = [
                'name' => $name,
                'email' => $user->email,
                'verificationLink' => env('APP_URL') . "/change-password/" . base64_encode($user->email)
            ];

            $sendForGotEmail = Mail::send('email_templates.forgot_password', $data, function ($message) use ($user, $name) {
                $message->to($user->email, $name)->subject('Change Password');
                $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
            });

            return response()->json([
                'status' => true,
                'message' => 'We have sent you a email with a change password link. Please check email and proceed further.',
                'data' => []
            ]);
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
     * @method changePassword: Function to handle change password feature.
     *
     * @param ChangePasswordRequest $request: Contains valid change password data.
     *
     * @return JSON response.
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $userEmail = base64_decode($request->token);
        $user = User::where('email', $userEmail)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token.',
                'data' => []
            ], 404);
        }

        $user->password = bcrypt($request->password);
        $user->password_changed_at = Carbon::now();
        if ($user->save()) {
            $message = "Password changed successfully.";
            $status = true;
            $errCode = 200;
        } else {
            $status = false;
            $message = "Something went wrong.";
            $errCode = 400;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => []
        ], $errCode);
    }

    /**
     * @method recoverPassword : Function to recover password.
     */
    public function recoverPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }
        $user = User::whereEmail(base64_decode($request->hash_code))->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Link Expired!',
                'data' => []
            ], 422);
        } else {
            $user->password = bcrypt($request->password);
            $user->password_changed_at = Carbon::now();
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully',
                'data' => []
            ], 200);
        }
    }

    /**
     * @method confirmEmail: Function to confirm user's email address.
     *
     * @param Request $request.
     * @param string email : base64 encoded email address.
     *
     */
    public function confirmEmail(Request $request, string $email)
    {
        $userEmail = base64_decode($email);
        $getUser = User::where('email', $userEmail)->first();

        if (!$getUser) {
            return redirect(env('APP_URL').'/sign-in?emailConfirmed=0');
        }

        if ($getUser->is_confirmed == 0) {
            $getUser->is_confirmed = 1;
            if ($getUser->save()) {
                return redirect(env('APP_URL').'/sign-in?emailConfirmed=1');
            }
            return redirect(env('APP_URL').'/sign-in?emailConfirmed=0');
        } else {
            return redirect(env('APP_URL').'/sign-in?emailConfirmed=2');
        }
    }

    /**
     * @method profile: Get the authenticated User
     *
     * @return JSON
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'User details.',
            'data' => $request->user()
        ]);
    }

    /**
     * @method updateProfile: Function to update user profile.
     *
     * @return JSON
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        if ($user->role_id == config('constant.roles.Customer') || $user->role_id == config('constant.roles.Haulers')) {
            try {
                
                
                $imageName = $user->putImage($request->user_image);
                $user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->province,
                    'zip_code' => $request->zipcode,
                    'user_image' => $imageName
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Your is successfully updated.',
                    'data' => $user
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => json_encode($e->getMessage()),
                    'data' => []
                ], 500);
            }
        }
    }
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out.',
            'data' => []
        ]);
    }
}
