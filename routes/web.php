<?php

use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

//Route::get('/public-chat-api',[ChatController::class, 'publicChat']);
//Route::get('/public-chat', function () {
//    return view('layouts/app');
//});
//Route::get('/public-chat-api', function () {
//    $data = array(
//        'jobId' => 44,
//    );
//    $postData = json_encode($data);
//
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, "http://" . env('SOCKET_SERVER_IP') . ":" . env('SOCKET_SERVER_PORT') . "/job-chat");
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
//    curl_setopt($ch, CURLOPT_POST, 1);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
//    $output = curl_exec($ch);
//    curl_close($ch);
//
//    // dd($output);
//    $messages = json_decode($output);
////    $messages = array_reverse($messages);
//    dd($messages);
//});

Route::get('/invoice/{q}/{type}', 'QuickbooksController@getInvoices'); //QuickBooks Invoice

Route::get('confirm-update-email/{email}/{id}', 'AuthController@confirmUpdateEmail');

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
