<?php


namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class ParentDirectory  implements Rule
{

    public function __construct()
    {

    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // TODO: Implement passes() method.
        return !Str::contains($value,'..');
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function message()
    {
        // TODO: Implement message() method.
        return trans('validation.parent_directory');
    }
}
