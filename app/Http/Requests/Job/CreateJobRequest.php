<?php

namespace App\Http\Requests\Job;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Auth;

class CreateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'service_id' => 'required',
            'payment_mode' => 'required',
            'job_providing_date' => 'required|date|date_format:Y-m-d',
            'job_providing_time' => 'nullable',
            'is_repeating_job' => 'required',
            'amount' => 'required',
            'repeating_days' => 'required_if:is_repeating_job,==,true',
        ];

        if(Auth::user()->role_id == config('constant.roles.Customer')) {
            $rules['manager_id'] = 'required';
            $rules['farm_id'] = 'required';
        }
        if (Auth::user()->role_id == config('constant.roles.Haulers')) {
            $rules['manager_id'] = 'required';
            $rules['job_providing_time'] = 'required';
        }
        if(Auth::user()->role_id == config('constant.roles.Hauler_driver')) {
            $rules['job_providing_time'] = 'required';
        }
        return $rules;
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'message' => $validator->errors(),
            'status' => false,
        ], 422));
    }
}