<?php
namespace concepture\core\validator;

class IntegerValidator extends Validator
{
    protected $message = "value is not integer";

    public function validate($value)
    {
        if (empty($value)){
            return true;
        }

        return filter_var($value, FILTER_VALIDATE_INT);
    }
}