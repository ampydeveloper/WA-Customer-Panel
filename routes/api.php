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
    Route::post('signup', 'AuthController@signup')->name('signup');
    Route::get('confirm-email/{decode_code}', 'AuthController@confirmEmail')->name('confirm.email');
    Route::post('social-signup', 'AuthController@SocialSignup')->name('social.login');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('forgot-password', 'AuthController@forgotPassword')->name('forgot.password');
    Route::post('change-password', 'AuthController@changePassword')->name('change.password');
    Route::get('logout', 'AuthController@logout')->middleware('auth:api')->name('logout');
    Route::get('profile', 'AuthController@profile')->middleware('auth:api')->name('profile');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'customer', 'as' => 'customer'], function () {

    // Services related routes
    Route::group(['prefix' => 'service', 'as' => 'service'], function () {
        Route::get('list', 'ServiceController@list')->name('list');
        Route::get('{service}', 'ServiceController@get')->name('get');
    });

    // Route::group(['prefix' => 'job', 'as' => 'job'], function () {
    //     Route::put('', 'ServiceController@create')->name('create');
    //     Route::get('{job}', 'ServiceController@get')->name('get');
    //     Route::get('list', 'ServiceController@list')->name('get');
    // });

    // Farm related apis

    Route::group(['prefix' => 'farm', 'as' => 'farm'], function () {
        Route::put('', 'FarmController@create')->name('create');
        Route::get('{customer_farm}', 'FarmController@get')->name('get');
        Route::get('list', 'FarmController@list')->name('get');
    });

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
