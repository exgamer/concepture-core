<?php
namespace concepture\repository;

use concepture\base\Component;
use concepture\base\ConnectionInterface;
use concepture\base\ModifyInterface;
use concepture\base\ReadInterface;

abstract class Repository extends Component implements ReadInterface, ModifyInterface, ConnectionInterface
{

}