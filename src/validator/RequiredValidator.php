<?php
namespace concepture\core\validator;

class RequiredValidator extends Validator
{
    protected $message = "value is required";

    public function validate($value)
    {
        if ($value === null){
            return false;
        }

        return true;
    }
}