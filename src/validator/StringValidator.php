<?php
namespace concepture\core\validator;

class StringValidator extends Validator
{
    protected $message = "value is not string";

    public function validate($value)
    {
        if (empty($value)){
            return true;
        }

        return is_string($value);
    }
}