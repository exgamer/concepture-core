<?php
namespace concepture\core\storage;

use concepture\core\base\ConnectionInterface;
use concepture\core\base\DataReadConfig;
use concepture\core\base\ModifyInterface;
use concepture\core\base\ReadInterface;

abstract class DbStorage extends Storage implements ReadInterface, ModifyInterface, ConnectionInterface
{
    public function insert($params)
    {
        // TODO: Implement insert() method.
    }

    public function update($params, $condition)
    {
        // TODO: Implement update() method.
    }

    public function delete($condition)
    {
        // TODO: Implement delete() method.
    }

    public function one($condition)
    {
        // TODO: Implement one() method.
    }

    public function all($condition, DataReadConfig $config)
    {
        // TODO: Implement all() method.
    }
}