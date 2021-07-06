<?php


namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class EmojiChar implements Rule
{

    public function __construct() {

    }

    public function passes($attribute, $value)
    {
        // TODO: Implement passes() method.
        $mbLen = mb_strlen($value);
        $strArr = [];
        $return = false;
        for($i = 0 ; $i < $mbLen; $i ++) {
            $strArr[] = mb_substr($value, $i, 1, 'utf-8');
            if (strlen($strArr[$i]) >= 4) {
                $return = true;
            }
        }
        return !$return;
    }

    public function message()
    {
        // TODO: Implement message() method.
        return trans('validation.emoji');
    }
}
