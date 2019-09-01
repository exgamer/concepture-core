<?php
namespace concepture\core\storage;

use concepture\core\base\Component;
use concepture\core\base\ConnectionInterface;
use concepture\core\base\DataReadCondition;
use concepture\core\base\DataReadConfig;
use concepture\core\base\ModifyInterface;
use concepture\core\base\ReadInterface;

abstract class Storage extends Component implements ReadInterface, ModifyInterface, ConnectionInterface
{
    public function insert($params)
    {
        // TODO: Implement insert() method.
    }

    public function update($params, DataReadCondition $condition)
    {
        // TODO: Implement update() method.
    }

    public function delete(DataReadCondition $condition)
    {
        // TODO: Implement delete() method.
    }

    public function one(DataReadCondition $condition)
    {
        // TODO: Implement one() method.
    }

    public function all(DataReadCondition $condition, DataReadConfig $config)
    {
        // TODO: Implement all() method.
    }
}