<?php

namespace App\Http\Requests\Api\Account\AccountType;

use App\Http\Requests\Api\FormRequest;

class UpdateAccountTypeRequest extends FormRequest
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
            'account_type_id' => ['bail', 'required', 'exists:account_types,id'],
            'name' => [''],
            'description' => [''],
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
            'account_type_id.required' => 'Please, enter a valid account type',
            'account_type_id.exists' => 'Please, enter an existing account type',
        ];
    }
}
