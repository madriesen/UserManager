<?php

namespace App\Http\Requests\Api\Authentication;

use App\Http\Requests\Api\FormRequest;

class LoginRequest extends FormRequest
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
            'email_address' => ['bail', 'required', 'email', 'exists:emails,address'],
            'password' => ['required']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email_address.required' => 'Please, enter a valid email address',
            'email_address.email' => 'Please, enter a valid email address',
            'email_address.exists' => 'Please, enter an existing email address',
            'password.required' => 'Please, enter a valid password',
        ];
    }
}
