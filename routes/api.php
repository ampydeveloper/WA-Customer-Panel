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

Route::group(['middleware' => 'auth:api', 'prefix' => 'my', 'as' => 'my'], function () {
    Route::get('farms', 'CustomerController@myFarms')->name('farms');
});
Route::group(['middleware' => 'auth:api', 'prefix' => 'customer', 'as' => 'customer'], function () {

    // Services related routes
    Route::group(['prefix' => 'service', 'as' => 'service'], function () {
        Route::get('list', 'ServiceController@list')->name('list');
        Route::get('{service}', 'ServiceController@get')->name('get');
    });

    // Farm related apis
    Route::group(['prefix' => 'farm', 'as' => 'farm'], function () {
        Route::put('', 'FarmController@create')->name('create');
        Route::patch('{customer_farm}', 'FarmController@update')->name('create');
        Route::get('{customer_farm}', 'FarmController@get')->name('get');
        Route::delete('{customer_farm}', 'FarmController@deleteFarm')->name('delete');

        Route::get('manager/is-unique/{email}', 'FarmController@isUniqueManager')->name('manage.is-unique');

        // Route group to manage farm managers
        Route::group(['prefix' => '{customer_farm}', 'as' => 'manager'], function () {
            Route::get('managers', 'FarmController@getFarmManagers')->name('list');
            Route::put('manager', 'FarmController@createFarmManager')->name('create');
            Route::patch('manager', 'FarmController@updateFarmManager')->name('update');
            Route::delete('manager/{user}', 'FarmController@deleteFarmManager')->name('delete');
        });
    });

     // Job related apis
     Route::group(['prefix' => 'job', 'as' => 'job'], function () {
        Route::get('{job}', 'JobController@get')->name('get');
        Route::put('', 'JobController@create')->name('create');
     });

});
