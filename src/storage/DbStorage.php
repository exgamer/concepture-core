<?php
namespace concepture\core\storage;

use concepture\core\base\DataReadConfig;
use concepture\core\base\ModifyInterface;
use concepture\core\base\ModifyQueryBuilder;
use concepture\core\base\ReadInterface;
use concepture\core\helpers\ClassHelper;
use concepture\core\helpers\StringHelper;

abstract class DbStorage extends Storage implements ReadInterface, ModifyInterface
{
    protected $connection;

    public function getConnection()
    {
        if (! $this->connection){
            throw new \Exception("Please set Db Connection");
        }
        return $this->connection;
    }

    public function getTableName()
    {
        $className = get_class($this);
        $name = ClassHelper::getName($className, "Storage");

        return  StringHelper::fromCamelCase($name);
    }

    public function insert($params)
    {
        $builder = new ModifyQueryBuilder();
        $builder->table($this->getTableName());
        $builder->data($params);
        $builder->makeInsertSql();
        $sql = $builder->getSql();
        $params = $builder->getParams();
        $stmt = $this->getConnection()->prepare($sql);
        foreach ($params as $name => $value){
            $stmt->bindValue($name, $value);
        }

        return $stmt->execute();
    }

    public function update($params, $condition)
    {
        $builder = new ModifyQueryBuilder();
        $builder->table($this->getTableName());
        $builder->data($params);
        $builder->andWhere($condition);
        $builder->makeUpdateSql();
        $sql = $builder->getSql();
        $params = $builder->getParams();
        $stmt = $this->getConnection()->prepare($sql);
        foreach ($params as $name => $value){
            $stmt->bindValue($name, $value);
        }

        return $stmt->execute();
    }

    public function delete($condition)
    {
        $builder = new ModifyQueryBuilder();
        $builder->table($this->getTableName());
        $builder->andWhere($condition);
        $builder->makeDeleteSql();
        $sql = $builder->getSql();
        $params = $builder->getParams();
        $stmt = $this->getConnection()->prepare($sql);
        foreach ($params as $name => $value){
            $stmt->bindValue($name, $value);
        }

        return $stmt->execute();
    }

    public function one($condition, DataReadConfig $config = null)
    {
        // TODO: Implement one() method.
    }

    public function all($condition, DataReadConfig $config)
    {
        // TODO: Implement all() method.
    }
}