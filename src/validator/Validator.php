<?php
namespace concepture\core\validator;

use concepture\core\base\Component;

abstract class Validator extends Component
{
    protected $message = "";

    public abstract function validate($value);

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}