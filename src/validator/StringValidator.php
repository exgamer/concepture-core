<?php
namespace concepture\core\validator;

class StringValidator extends Validator
{
    protected $message = "value is not string";

    public function validate($value)
    {

        return is_string($value);
    }
}