<?php
namespace concepture\core\repository;

use concepture\core\base\Component;
use concepture\core\base\ConnectionInterface;
use concepture\core\base\ModifyInterface;
use concepture\core\base\ReadInterface;

abstract class Repository extends Component implements ReadInterface, ModifyInterface, ConnectionInterface
{

}