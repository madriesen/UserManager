<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\FormRequest;
use App\Rules\Tel;

class CreateProfileRequest extends FormRequest
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
            'account_id' => ['bail', 'required', 'exists:accounts,id'],
            'first_name' => ['required', 'max:255'],
            'name' => ['required', 'max:255'],
            'tel' => ['numeric'],
            'birthday' => ['date', 'before:now'],
            'profile_picture_url' => ['max:510']
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
            'account_id.required' => 'Please, enter a valid account',
            'account_id.exists' => 'Please, enter an existing account',
            'first_name.required' => 'Please, enter a first name',
            'name.required' => 'Please, enter a name',
            'first_name.max' => 'The first name is too long',
            'name.max' => 'The name is too long',
            'tel.numeric' => 'The phone number can only contain numbers',
        ];
    }
}
