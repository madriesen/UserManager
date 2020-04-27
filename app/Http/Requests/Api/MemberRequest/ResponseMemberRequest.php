<?php

namespace App\Http\Requests\Api\MemberRequest;

use App\Http\Requests\Api\FormRequest;

class ResponseMemberRequest extends FormRequest
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
            'member_request_uuid' => 'required|exists:member_requests,uuid',
            'response',
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
            'member_request_uuid.required' => 'Please, enter a valid member request uuid',
            'member_request_uuid.exists' => 'Please, enter an existing member request uuid',
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
            'member_request_uuid' => 'trim',
        ];
    }


}
