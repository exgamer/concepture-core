<?php
namespace concepture\core\filter;

use concepture\core\base\Component;

abstract class Filter extends Component
{

    public abstract function filter($value);

}