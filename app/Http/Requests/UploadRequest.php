<?php

namespace App\Http\Requests;

use App\Rules\ContinuousCharacter;
use App\Rules\EmojiChar;
use App\Rules\ParentDirectory;
use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
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
            'file' => ['required', 'file'],
            'directory' => [
                'required', 'string', 'between:1,60', new EmojiChar, new ParentDirectory, new ContinuousCharacter
            ],
            'name' => ['nullable', 'min:1', 'max:1023']
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'file' => __('message.file.file'),
            'directory' => __('message.file.directory'),
            'name' => __('message.file.name'),
        ];
    }
}
