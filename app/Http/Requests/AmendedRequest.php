<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmendedRequest extends FormRequest
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
            'id' => ['required', 'string', 'exists:exception_errors',],
            'solve' => ['required', 'integer',]
        ];
    }
    public function attributes()
    {
        return [
            'id' => __('message.exception.id'),
            'solve' => __('message.exception.solve'),
        ];
    }
}
