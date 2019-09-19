<?php
namespace concepture\core\storage;

use concepture\core\base\BaseReadQueryBuilder;
use concepture\core\base\ModifyInterface;
use concepture\core\base\ModifyQueryBuilder;
use concepture\core\base\ReadInterface;
use concepture\core\base\ReadQueryBuilder;
use concepture\core\helpers\ClassHelper;
use concepture\core\helpers\StringHelper;

abstract class DbStorage extends Storage implements ReadInterface, ModifyInterface
{
    protected $connection;

    /**
     * @return \PDO
     * @throws \Exception
     */
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

    public function updateById($id, $params)
    {
        $condition = [
            'id' => $id
        ];

        return $this->update($params, $condition);
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

    public function oneById($id)
    {
        $builder = new ReadQueryBuilder();
        $builder->andEqualCondition(['id' => $id]);

        return $this->fetchOne($builder);
    }

    public function allByIds($ids)
    {
        $builder = new ReadQueryBuilder();
        $builder->andInCondition('id', $ids);

        return $this->fetchAll($builder);
    }

    protected function fetchOne(BaseReadQueryBuilder $builder)
    {
        $this->extendBuilder($builder);
        $builder->makeSelectSql();
        $sql = $builder->getSql();
        $params = $builder->getParams();
        $stmt = $this->getConnection()->prepare($sql);
        foreach ($params as $name => $value){
            $stmt->bindValue($name, $value);
        }
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    protected function fetchAll(BaseReadQueryBuilder $builder)
    {
        $this->extendBuilder($builder);
        $builder->makeSelectSql();
        $sql = $builder->getSql();
        $params = $builder->getParams();
        $stmt = $this->getConnection()->prepare($sql);
        foreach ($params as $name => $value){
            $stmt->bindValue($name, $value);
        }
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function extendBuilder($builder)
    {
        if ($builder instanceof ReadQueryBuilder) {
            $builder->from($this->getTableName());
        }
    }
}