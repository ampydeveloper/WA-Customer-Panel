<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use App\Models\User;
use App\Models\TimeSlots;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
   
   /**
     * @method get : Function to get service.
     *
     * Return JSON Response.
     */
    public function get(Service $service)
    {
        $user = request()->user();

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

    /**
     * @method list : List services on the bases of logged in customer type.
     *
     * Return JSON Response.
     */
    
    public function serviceList() {
        $user = request()->user();
        
        if ($user->role_id != config('constant.roles.Customer') && $user->role_id != config('constant.roles.Haulers') && $user->role_id != config('constant.roles.Customer_Manager')) {
            return response()->json([
                        'status' => false,
                        'message' => 'unauthorized access.',
                        'data' => []
                            ], 421);
        }
        if ($user->role_id == config('constant.roles.Customer') || $user->role_id == config('constant.roles.Haulers')) {
            $getAllServices = Service::where('service_for', $user->role_id)->get();
        } else {
            if ($user->managerOf) {
                $customer = User::find($user->managerOf->customer_id);
                $getAllServices = Service::where('service_for', $customer->role_id)->get();
            } else {
                $getAllServices = [];
            }
        }
        return response()->json([
                    'status' => true,
                    'message' => 'Service Listing.',
                    'data' => $getAllServices
                        ], 200);
    }
}
