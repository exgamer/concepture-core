<?php
namespace concepture\core\storage;

use concepture\core\helpers\ClassHelper;
use concepture\core\helpers\StringHelper;
use concepture\core\traits\StorageModifyMethodsTrait;
use concepture\core\traits\StorageReadMethodsTrait;
use Exception;
use PDO;

abstract class Storage extends BaseStorage
{
    use StorageModifyMethodsTrait;
    use StorageReadMethodsTrait;

    protected $connection;

    /**
     * @return PDO
     * @throws Exception
     */
    protected function getConnection()
    {
        if (! $this->connection){
            throw new Exception("Please set Db Connection");
        }
        return $this->connection;
    }

    protected function getTableName()
    {
        $className = get_class($this);
        $name = ClassHelper::getName($className, ClassHelper::getName(self::class));

        return  StringHelper::fromCamelCase($name);
    }
}