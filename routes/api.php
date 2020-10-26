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
    //done
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('forgot-password', 'AuthController@forgotPassword')->name('forgot.password');
    Route::post('change-password', 'AuthController@changePassword')->name('change.password');
    Route::get('logout', 'AuthController@logout')->middleware('auth:api')->name('logout');
    Route::group(['prefix' => 'profile', 'as' => 'profile'], function () {
        //done
        Route::get('', 'AuthController@profile')->middleware('auth:api')->name('get');
        //done
        Route::post('', 'AuthController@updateProfile')->middleware('auth:api')->name('update');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'my', 'as' => 'my'], function () {
    //done
    Route::get('farms', 'CustomerController@myFarms')->name('farms');
    //done
    Route::get('jobs', 'JobController@myJobs')->name('jobs');
    //done
    Route::get('jobs/upcoming', 'JobController@myUpcomingJobs')->name('upcoming.jobs');
});
Route::group(['middleware' => 'auth:api', 'prefix' => 'customer', 'as' => 'customer'], function () {

    // Services related routes
    Route::group(['prefix' => 'service', 'as' => 'service'], function () {
        //done
        Route::get('list', 'ServiceController@serviceList')->name('list');
        //done
        Route::get('{service}', 'ServiceController@get')->name('get');
    });

    // Farm related apis
    Route::group(['prefix' => 'farm', 'as' => 'farm'], function () {
        //Done
        Route::post('', 'FarmController@create')->name('create');
        //done
        Route::patch('{customer_farm}', 'FarmController@update')->name('update');
        //done
        Route::get('{customer_farm}', 'FarmController@get')->name('get');
        //done
        Route::delete('{customer_farm}', 'FarmController@deleteFarm')->name('delete');

        Route::get('manager/is-unique/{email}', 'FarmController@isUniqueManager')->name('manage.is-unique');

        // Route group to manage farm managers
        Route::group(['prefix' => '{customer_farm}', 'as' => 'manager'], function () {
            //done
            Route::get('managers', 'FarmController@getFarmManagers')->name('list');
            //done
            Route::put('manager', 'FarmController@createFarmManager')->name('create');
            
            Route::patch('manager', 'FarmController@updateFarmManager')->name('update');
            Route::delete('manager/{user}', 'FarmController@deleteFarmManager')->name('delete');
        });
        Route::group(['prefix' => '{customer_farm}', 'as' => 'jobs'], function () {
            //done
            Route::get('jobs', 'JobController@getJobsOfFram')->name('list');
            //done
            Route::get('jobs/upcoming', 'JobController@upcomingJobs')->name('list');
        });
    });

    // Job related apis
    Route::group(['prefix' => 'job', 'as' => 'job'], function () {
        //done
        Route::post('', 'JobController@create')->name('create');
        //done
        Route::patch('', 'JobController@update')->name('create');
        //done
        Route::get('{job}/cancel', 'JobController@cancelJob')->name('cancel');
        //done
        Route::get('{job}', 'JobController@get')->name('get');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'payment', 'as' => 'payment'], function () {
    Route::post('charge', 'PaymentController@chargeCustomerProfile')->name('charge');
    Route::group(['prefix' => 'customer', 'as' => 'customer'], function () {
        Route::put('add-card', 'PaymentController@addCard')->name('create');
        //done
        Route::get('cards', 'PaymentController@getCustomerPaymentProfileList')->name('cards');
        Route::delete('card/{customerCardDetails}', 'PaymentController@deleteCustomerPaymentProfile')->name('card.delete');
        Route::get('card/make-default/{customerCardDetails}', 'PaymentController@updateCustomerPaymentProfile')->name('card.make-default');
    });
});