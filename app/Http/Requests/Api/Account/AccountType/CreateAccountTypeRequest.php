<?php

namespace App\Http\Requests\Api\Account\AccountType;

use App\Http\Requests\Api\FormRequest;

class CreateAccountTypeRequest extends FormRequest
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
            'title' => ['bail', 'required',],
            'description' => ['required',],
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
            'title.required' => 'Please, enter a valid title',
            'description.required' => 'Please, enter a valid description',
        ];
    }
}
