<?php

namespace App\Http\Requests\Api\MemberRequest;

use App\Http\Requests\Api\FormRequest;

class CreateMemberRequestRequest extends FormRequest
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
            'email_address' => 'bail|required|email|unique:emails,address',
            'name', 'first_name',
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
            'email_address.required' => 'Please, enter an email address',
            'email_address.email' => 'Please, enter a valid email address',
            'email_address.unique' => 'This email address already exists',
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'email' => 'trim|lowercase',
            'name' => 'trim|lowercase',
            'first_name' => 'trim|lowercase'
        ];
    }
}
