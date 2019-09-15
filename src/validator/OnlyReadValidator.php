<?php
namespace concepture\core\validator;

class OnlyReadValidator extends Validator
{
    protected $message = "";

    public function validate($value)
    {

        return true;
    }
}