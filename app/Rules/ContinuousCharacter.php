<?php


namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class ContinuousCharacter implements Rule
{

    private $str;

    /**
     * ContinuousCharacter constructor.
     * @param string $str
     */
    public function __construct(string $str = '\/')
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
        // TODO: Implement passes() method.
        return !preg_match("/{$this->str}{2}",$value);
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function message()
    {
        // TODO: Implement message() method.
        return trans('validation.continuous_character', ['values' => $this->str]);
    }

}
