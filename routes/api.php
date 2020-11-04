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
    //done
    Route::post('signup', 'AuthController@signup')->name('signup');
    Route::get('confirm-email/{decode_code}', 'AuthController@confirmEmail')->name('confirm.email');
    Route::post('social-signup', 'AuthController@SocialSignup')->name('social.login');
    //done
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('forgot-password', 'AuthController@forgotPassword')->name('forgot.password');
    Route::post('change-password', 'AuthController@changePassword')->name('change.password');
    //done
    Route::get('logout', 'AuthController@logout')->middleware('auth:api')->name('logout');
    Route::group(['prefix' => 'profile', 'as' => 'profile'], function () {
        //done
        Route::get('', 'AuthController@profile')->middleware('auth:api')->name('get');
        //done
        Route::post('', 'AuthController@updateProfile')->middleware('auth:api')->name('update');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('uploadImage', 'ImageController@uploadImage');
        Route::delete('deleteImage', 'ImageController@deleteImage');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'my', 'as' => 'my'], function () {
    //done
    Route::get('jobs', 'JobController@myJobs')->name('jobs');
    //done
    Route::get('jobs/upcoming', 'JobController@myUpcomingJobs')->name('upcoming.jobs');
    //done
    Route::get('farms', 'CustomerController@myFarms')->name('farms');
    //done
    Route::get('managers', 'CustomerController@myManagers')->name('list-all');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'customer', 'as' => 'customer'], function () {
    
    // Job related apis
    Route::group(['prefix' => 'job', 'as' => 'job'], function () {
        //done
        Route::post('', 'JobController@create')->name('create');
        //done
        Route::post('{job_id}', 'JobController@update')->name('update');
        //done
        Route::get('{job}/cancel', 'JobController@cancelJob')->name('cancel');
        //done
        Route::get('{job}', 'JobController@get')->name('get');
    });

    // Services related routes
    Route::group(['prefix' => 'service', 'as' => 'service'], function () {
        //done
        Route::get('list', 'ServiceController@serviceList')->name('list');
        //done
        Route::get('{service}', 'ServiceController@get')->name('get');
    });

    // Farm related apis
    Route::group(['prefix' => 'farm', 'as' => 'farm'], function () {
        
        Route::group(['prefix' => '{customer_farm}', 'as' => 'jobs'], function () {
            //done
            Route::get('jobs', 'JobController@getJobsOfFram')->name('list');
            //done
            Route::get('jobs/upcoming', 'JobController@upcomingJobsOfFarm')->name('upcoming-jobs-list');
        });
        //Done
        Route::post('', 'FarmController@create')->name('create');
        //done
        Route::get('{customer_farm}', 'FarmController@get')->name('get');
        //done
        Route::post('{customer_farm}', 'FarmController@update')->name('update');
        //done
        Route::delete('{customer_farm}', 'FarmController@deleteFarm')->name('delete');

//        Route::get('manager/is-unique/{email}', 'FarmController@isUniqueManager')->name('manage.is-unique');

        // Route group to manage farm managers
        Route::group(['prefix' => '{customer_farm}', 'as' => 'manager'], function () {
            //done
            Route::get('managers', 'FarmController@getFarmManagers')->name('list');
            //done
            Route::post('manager', 'FarmController@createFarmManager')->name('create');
            //done
            Route::get('manager/{manager}', 'FarmController@getFarmManager')->name('single-farm-manager');
            //done
            Route::post('manager/{manager}', 'FarmController@updateFarmManager')->name('update');
            //done
            Route::delete('manager/{user}', 'FarmController@deleteFarmManager')->name('delete');
            //done
            Route::get('change-manager/{user}', 'FarmController@changeManager')->name('change-manager');
        });
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'hauler/driver', 'as' => 'hauler/driver'], function () {

    //done
    Route::get('all', 'DriverController@allDriversList')->name('all-drivers');
    //done
    Route::post('create', 'DriverController@create')->name('create');
    //done
    Route::post('{driver}', 'DriverController@update')->name('update');
    //done
    Route::get('{driver}', 'DriverController@get')->name('get');
    //done
    Route::delete('{driver}', 'DriverController@deleteDriver')->name('delete');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'payment', 'as' => 'payment'], function () {
    
    Route::post('charge', 'PaymentController@chargeCustomerProfile')->name('charge');
    Route::group(['prefix' => 'customer', 'as' => 'customer'], function () {
        //done
        Route::post('add-card', 'PaymentController@addCard')->name('create');
        //done
        Route::get('cards', 'PaymentController@getCustomerPaymentProfileList')->name('cards');
        //done
        Route::delete('card/{customerCardDetails}', 'PaymentController@deleteCustomerPaymentProfile')->name('card.delete');
        //done
        Route::get('card/make-default/{customerCardDetails}', 'PaymentController@updateCustomerPaymentProfile')->name('card.make-default');
    });
});