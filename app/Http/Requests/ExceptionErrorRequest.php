<?php

namespace App\Http\Requests;

use App\Http\Requests\ExceptionErrorRequest as CommonRequest;
use Illuminate\Foundation\Http\FormRequest;

class ExceptionErrorRequest extends FormRequest
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
        return array_merge((new CommonRequest())->rules(), [
            'id' => ['nullable', 'string',],
            'message' => ['nullable', 'string',],
            'is_solve' => ['nullable', 'integer',],
        ]);
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return array_merge((new CommonRequest())->attributes(), [
            'id' => __('message.exception.id'),
            'message' => __('message.exception.message'),
            'solve' => __('message.exception.solve'),
        ]);
    }
}
