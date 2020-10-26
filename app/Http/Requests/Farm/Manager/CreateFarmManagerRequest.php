<?php

namespace App\Http\Requests\Farm\Manager;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateFarmManagerRequest extends FormRequest
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
            'farm_id' => 'required',
            'manager_first_name' => 'required',
            'manager_last_name' => 'required',
            'email' => 'required|email|unique:users',
            'manager_phone' => 'required',
            'manager_address' => 'required',
            'manager_city' => 'required',
            'manager_province' => 'required',
            'manager_zipcode' => 'required',
            'manager_card_image' => 'required|image|max:5120',
            'manager_id_card' => 'required',
            'salary' => 'required',
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