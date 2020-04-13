<?php

namespace App\Http\Requests\Api\Invite;

use App\Http\Requests\Api\FormRequest;

class CreateInviteRequest extends FormRequest
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
            'member_request_id' => 'required|numeric|exists:member_requests,id',
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
            'member_request_id.required' => 'Please, enter a valid member request',
            'member_request_id.numeric' => 'Please, enter a valid member request',
            'member_request_id.exists' => 'Please, enter an existing member request',
        ];
    }
}
