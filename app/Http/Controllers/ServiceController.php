<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
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
    public function list()
    {
        $user = request()->user();
       
        if ($user->role_id != config('constant.roles.Customer') && $user->role_id != config('constant.roles.Haulers')) {
            return response()->json([
                'status' => false,
                'message' => 'unauthorized access.',
                'data' => []
            ], 421);
        }

        $getAllServices = Service::where('service_for', $user->role_id)->get();

        if (count($getAllServices) > 0) {
            foreach ($getAllServices as $key => $service) {
                if ($service->service_for == config('constant.roles.Customer')) {
                    $timeSlots = TimeSlots::whereIn('id', json_decode($service->slot_time))->get();
                    $getAllServices[$key]["timeSlots"] = $timeSlots;
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Service Listing.',
            'data' => $getAllServices
        ], 200);
    }


}
