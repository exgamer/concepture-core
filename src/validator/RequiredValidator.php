<?php
namespace concepture\core\validator;

class RequiredValidator extends Validator
{
    protected $message = "";

    public function validate($value)
    {
        if ($value === null){
            return false;
        }

        return true;
    }
}