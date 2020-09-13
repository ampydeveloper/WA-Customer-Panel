<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('signup', 'AuthController@signup');
    Route::get('confirm-email/{decode_code}', 'AuthController@confirmEmail');
    Route::post('social-signup', 'AuthController@SocialSignup');
    Route::post('login', 'AuthController@login');
    Route::post('forgot-password', 'AuthController@forgotPassword');
    Route::post('change-password', 'AuthController@changePassword');
    Route::get('logout', 'AuthController@logout')->middleware('auth:api');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'customer'], function () {
    // Route::get('list', 'CustomerController@listCustomer');
    // Route::get('{customer_id}', 'CustomerController@getCustomer');
    // Route::put('', 'CustomerController@createCustomer');
    // Route::patch('', 'CustomerController@updateCustomer');
    
    
    // Route::post('list-customer-mobile', 'CustomerController@listCustomerMobile');
    // Route::post('create-farm', 'CustomerController@createFarm');
    // Route::post('create-manager', 'CustomerController@createCustomerManager');


    // Route::get('get-farm/{customer_id}', 'CustomerController@getFarms');
    // Route::get('get-farm-and-manager/{customer_id}', 'CustomerController@getCustomerManager');
    // Route::get('get-farm-manager/{farm_id}', 'CustomerController@getFarmManager');
    // Route::get('card-list/{customer_id}', 'CustomerController@getAllCard');
    // Route::get('record-list/{customer_id}', 'CustomerController@getAllRecords');
    // Route::post('update-farm', 'CustomerController@updateFarm');
    // Route::post('update-manager', 'CustomerController@updateManager');
});
