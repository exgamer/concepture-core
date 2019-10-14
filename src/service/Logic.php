<?php
namespace concepture\core\service;

use concepture\core\base\Component;
use concepture\core\traits\LoggerTrait;

abstract class Logic extends Component
{
    use LoggerTrait;

    /**
     * Методы логгера
     */

    public function extendLogContext($context)
    {
        $context['service'] = $this->getName();

        return $context;
    }


    public function extendLogMessage($message)
    {
        return  $message." [".$this->getName()."]";
    }

    /**
     * Методы логгера end
     */
}