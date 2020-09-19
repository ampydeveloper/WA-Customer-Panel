<?php

namespace App\Http\Requests\Farm;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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