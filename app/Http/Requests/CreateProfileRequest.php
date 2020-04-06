<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'required|min:3|max:255',
            'firstname' => 'required|min:3|max:255',
            'tel' => 'numeric|size:14|tel',
            'birthday' => 'date|before:now',
            'profile_picture_url' => 'max:510'
        ];
    }
}
