<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Validation\Rule;
//use Illuminate\Support\Facades\DB;
//use App\Models\TimeSlots;
//use Illuminate\Support\Str;
use App\Models\News;
use App\Models\Faq;

class ServiceController extends Controller
{
    public function serviceList() {
        $user = request()->user();
        if ($user->role_id == config('constant.roles.Customer_Manager') || $user->role_id == config('constant.roles.Hauler_driver')) {
            $user = User::whereId(request()->user()->created_by)->first();
        }
        if ($user->role_id != config('constant.roles.Customer') && $user->role_id != config('constant.roles.Haulers') && $user->role_id != config('constant.roles.Customer_Manager') && $user->role_id != config('constant.roles.Hauler_driver')) {
            return response()->json([
                        'status' => false,
                        'message' => 'unauthorized access.',
                        'data' => []
                            ], 421);
        } else {
            $getAllServices = Service::where('service_for', $user->role_id)->get();
            return response()->json([
                    'status' => true,
                    'message' => 'Service Listing.',
                    'data' => $getAllServices
                        ], 200);
        }
    }
    
    public function serviceForAll() {
            $getAllServices = Service::get();
            return response()->json([
                    'status' => true,
                    'message' => 'Service Listing.',
                    'data' => $getAllServices
                        ], 200);
    }
   
     public function newsList() {
        return response()->json([
                    'status' => true,
                    'message' => 'News List',
                    'data' => News::get()
                        ], 200);
    }
    
    public function newsListTwo() {
        return response()->json([
                    'status' => true,
                    'message' => 'News List',
                    'data' => News::limit(2)->get()
                        ], 200);
    }
    
    public function faqList() {
        return response()->json([
                    'status' => true,
                    'message' => 'Faq List',
                    'data' => Faq::get()
                        ], 200);
    }
    
    public function get(Service $service)
    {
        $user = request()->user();
        if ($user->role_id == config('constant.roles.Customer_Manager') || $user->role_id == config('constant.roles.Hauler_driver')) {
            $user = User::whereId(request()->user()->created_by)->first();
        }
        if ($user->role_id != $service->service_for) {
            return response()->json([
                        'status' => false,
                        'message' => 'unauthorized access.',
                        'data' => []
                            ], 421);
        }
        return response()->json([
                    'status' => true,
                    'message' => 'Service details.',
                    'data' => $service
                        ], 200);
    }

    
}
