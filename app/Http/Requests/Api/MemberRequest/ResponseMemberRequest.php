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
            'member_request_id' => 'required|exists:member_requests,id',
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
            'member_request_id.required' => 'Please, enter a valid member request id',
            'member_request_id.exists' => 'Please, enter a valid member request id',
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
            'member_request_id' => 'trim',
        ];
    }


}
