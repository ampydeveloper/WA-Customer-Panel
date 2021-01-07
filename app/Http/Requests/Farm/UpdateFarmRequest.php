<?php

namespace App\Http\Requests\Farm;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateFarmRequest extends FormRequest
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
        return [
            'farm_address' => 'required',
            'farm_city' => 'required',
            'farm_province' => 'required',
            'farm_zipcode' => 'required',
            'farm_active' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            // 'manager_details.*.id' => 'required',
            'manager_details.*.manager_first_name' => 'required',
            'manager_details.*.manager_last_name' => 'required',
            'manager_details.*.email' => ['required', 'email'],
            'manager_details.*.manager_phone' => 'required',
            'manager_details.*.manager_address' => 'required',
            'manager_details.*.manager_city' => 'required',
            'manager_details.*.manager_province' => 'required',
            'manager_details.*.manager_zipcode' => 'required',
            // 'manager_details.*.is_active' => 'required',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'message' => 'The given data is invalid.',
            'status' => false,
        ], 422));
    }
}