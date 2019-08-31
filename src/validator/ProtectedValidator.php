<?php
namespace concepture\core\validator;

class ProtectedValidator extends Validator
{
    protected $message = "";

    public function validate($value)
    {

        return true;
    }
}