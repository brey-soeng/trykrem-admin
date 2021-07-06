<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

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
     * @return
     */
    public function rules()
    {
        return [
            'username' => ['required','string','between:2,60'],
            'password' => ['required', 'string', 'min:6']
        ];
    }

    /**
     * @return array
     */

    public function attributes()
    {
        return [
            'username' => __('validation.attributes.username'),
            'password' =>  __('validation.attributes.password'),
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }
}
