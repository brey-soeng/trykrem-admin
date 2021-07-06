<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AllRoleRequest extends FormRequest
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
            'guard_name' => ['required', 'string', Rule::in(['api', 'admin']),]
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'guard_name' => __('message.permission.guard_name'),
        ];
    }
}
