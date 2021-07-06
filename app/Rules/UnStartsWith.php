<?php


namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class UnStartsWith  implements Rule
{
    private $str;

    /**
     * UnStartsWith constructor.
     * @param string $str
     */
    public function __construct(string $str = '/')
    {
        $this->str = $str;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !Str::startsWith($value, $this->str);
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function message()
    {
        return trans('validation.un_starts_with', ['values' => $this->str]);
    }
}
