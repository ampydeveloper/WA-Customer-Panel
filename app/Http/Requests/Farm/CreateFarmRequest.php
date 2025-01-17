<?php

namespace App\Http\Requests\Farm;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateFarmRequest extends FormRequest
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
//            'farm_active' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'farm_image.*' => 'sometimes|required|image|max:5120',
    
            'manager_details.*.manager_first_name' => 'required',
            'manager_details.*.manager_last_name' => 'required',
            'manager_details.*.email' => 'required|email|unique:users',
            'manager_details.*.manager_phone' => 'required',
            'manager_details.*.manager_address' => 'required',
            'manager_details.*.manager_city' => 'required',
            'manager_details.*.manager_province' => 'required',
            'manager_details.*.manager_zipcode' => 'required',
//            'manager_details.*.manager_card_image' => 'required|image|max:5120',
//            'manager_details.*.manager_id_card' => 'required',
//            'manager_details.*.salary' => 'required'
        ];
    }

    public function messages(){
        $messages = [];
        foreach($this->get('manager_details') as $key => $val){
            $messages['manager_details.'.$key.'.email.unique'] = "Manager email ".$val['email']." has been already taken!";
        }
        return $messages;
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'message' => $validator->messages(),
            'status' => false,
        ], 422));
    }
}