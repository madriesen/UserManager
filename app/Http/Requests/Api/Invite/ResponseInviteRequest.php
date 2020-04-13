<?php

namespace App\Http\Requests\Api\Invite;

use App\Http\Requests\Api\FormRequest;

class ResponseInviteRequest extends FormRequest
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
            'invite_id' => 'required|exists:invites,id',
            'accept', 'decline'
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
            'invite_id.required' => 'Please, enter a valid invite',
            'invite_id.exists' => 'Please, enter an existing invite',
        ];
    }
}
