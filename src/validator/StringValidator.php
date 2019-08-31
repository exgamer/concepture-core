<?php
namespace concepture\core\validator;

class StringValidator extends Validator
{
    protected $message = "";

    public function validate($value)
    {

        return is_string($value);
    }
}