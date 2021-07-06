<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class SysnPermissionRequest extends FormRequest
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

        $tableNames = config('permission.table_names');
        return [
            'id' => ['required', 'integer', 'exists:' . $tableNames['roles']],
            'permissions' => ['array']
        ];
    }

    public function attributes()
    {
        return
        [
            'id' => __('message.role.id'),
            'permissions' => __('message.role.permissions'),
        ];
    }
}
