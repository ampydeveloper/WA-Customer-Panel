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
Route::group(['prefix' => 'customer', 'as' => 'customer'], function () {
    // Services related routes
    Route::group(['prefix' => 'service', 'as' => 'service'], function () {
       Route::get('all', 'ServiceController@serviceForAll')->name('all');
        });
         Route::get('news-list', 'ServiceController@newsList')->name('news-list');
         Route::get('news-single/{newsId}', 'ServiceController@newsSingle')->name('news-single');
         Route::get('news-two', 'ServiceController@newsListTwo')->name('news-two');
         Route::get('faq-list', 'ServiceController@faqList')->name('faq-list');
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('signup', 'AuthController@signup')->name('signup');
    Route::get('confirm-email/{decode_code}', 'AuthController@confirmEmail')->name('confirm.email');
    Route::post('social-signup', 'AuthController@SocialSignup')->name('social.login');
    Route::post('login', 'AuthController@login')->name('login');
    
    
    Route::post('send-otp', 'AuthController@sendOtp');
    Route::post('check-otp', 'AuthController@checkOtp');
    Route::post('forget-password-mobile', 'AuthController@forgotPasswordMobile');
    
    
    Route::post('forgot-password', 'AuthController@forgotPassword')->name('forgot.password');
    Route::post('change-password-web', 'AuthController@changePassword')->name('change.password');
    Route::post('change-password', 'AuthController@changePasswordMobile')->middleware('auth:api')->name('change.password.mobile');
    Route::get('logout', 'AuthController@logout')->middleware('auth:api')->name('logout');
    Route::group(['prefix' => 'profile', 'as' => 'profile'], function () {
        Route::get('', 'AuthController@profile')->middleware('auth:api')->name('get');
        Route::post('', 'AuthController@updateProfile')->middleware('auth:api')->name('update');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('uploadImage', 'ImageController@uploadImage');
        Route::delete('deleteImage', 'ImageController@deleteImage');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'my', 'as' => 'my'], function () {
    Route::get('jobs/{page_no?}', 'JobController@myJobs')->name('jobs');
    // Route::get('jobs-mobile/{page_no?}', 'JobController@myJobsMobile')->name('jobs');
    Route::get('jobs/upcoming', 'JobController@myUpcomingJobs')->name('upcoming.jobs');
    Route::get('farms/{page_no?}', 'CustomerController@myFarms')->name('farms');
    Route::get('managers/{id?}/{page_no?}', 'CustomerController@myManagers')->name('list-all');
    
     Route::post('job-chat', 'JobController@jobChat');
    Route::get('chat-members/{job_id}', 'JobController@chatMembers');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'customer', 'as' => 'customer'], function () {
    
    // Services related routes
    Route::group(['prefix' => 'service', 'as' => 'service'], function () {
        Route::get('list', 'ServiceController@serviceList')->name('list');
        Route::get('{service}', 'ServiceController@get')->name('get');
    });
    
    // Job related apis
    Route::group(['prefix' => 'job', 'as' => 'job'], function () {
        Route::post('', 'JobController@create')->name('create');
        Route::post('{job_id}', 'JobController@update')->name('update');
        Route::get('{job}/cancel', 'JobController@cancelJob')->name('cancel');
        Route::get('{job}', 'JobController@get')->name('get');
    });

    // Farm related apis
    Route::group(['prefix' => 'farm', 'as' => 'farm'], function () {
        Route::group(['prefix' => '{customer_farm}', 'as' => 'jobs'], function () {
            Route::get('jobs', 'JobController@getJobsOfFram')->name('list');
            Route::get('jobs/upcoming', 'JobController@upcomingJobsOfFarm')->name('upcoming-jobs-list');
        });
        Route::post('', 'FarmController@create')->name('create');
        Route::get('{customer_farm}', 'FarmController@get')->name('get');
        Route::post('{customer_farm}', 'FarmController@update')->name('update');
        Route::delete('{customer_farm}', 'FarmController@deleteFarm')->name('delete');
        Route::get('manager/is-unique/{email}', 'FarmController@isUniqueManager')->name('manage.is-unique');

        // Route group to manage farm managers
        Route::group(['prefix' => '{customer_farm}', 'as' => 'manager'], function () {
            Route::get('managers', 'FarmController@getFarmManagers')->name('list');
            Route::post('manager', 'FarmController@createFarmManager')->name('create');
            Route::get('manager/{manager}', 'FarmController@getFarmManager')->name('single-farm-manager');
            Route::post('manager/{manager}', 'FarmController@updateFarmManager')->name('update');
            Route::delete('manager/{user}', 'FarmController@deleteFarmManager')->name('delete');
            Route::get('change-manager/{user}', 'FarmController@changeManager')->name('change-manager');
        });
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'hauler/driver', 'as' => 'hauler/driver'], function () {
    Route::get('all', 'DriverController@allDriversList')->name('all-drivers');
    Route::post('create', 'DriverController@create')->name('create');
    Route::post('{driver}', 'DriverController@update')->name('update');
    Route::get('{driver}', 'DriverController@get')->name('get');
    Route::delete('{driver}', 'DriverController@deleteDriver')->name('delete');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'payment', 'as' => 'payment'], function () {
    Route::post('charge', 'PaymentController@chargeCustomerProfile')->name('charge');
    Route::group(['prefix' => 'customer', 'as' => 'customer'], function () {
        Route::put('add-card', 'PaymentController@addCard')->name('create');
        Route::get('cards', 'PaymentController@getCustomerPaymentProfileList')->name('cards');
        Route::delete('card/{customerCardDetails}', 'PaymentController@deleteCustomerPaymentProfile')->name('card.delete');
        Route::get('card/make-default/{customerCardDetails}', 'PaymentController@updateCustomerPaymentProfile')->name('card.make-default');
    });
});