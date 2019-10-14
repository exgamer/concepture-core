<?php
namespace concepture\core\traits;

use concepture\core\base\BaseReadQueryBuilder;
use concepture\core\base\ReadCondition;
use concepture\core\base\ReadQueryBuilder;


trait StorageReadMethodsTrait
{

    /**
     * Возвращает одну запись по id
     * @param int $id
     *
     * Пример расширения запроса через $callback
     * function(ReadQueryBuilder $builder) {
     *       $builder->andWhere("object_type = :object_type", [':object_type' => 2]);
     * }
     *
     * @param array|callable $condition
     * @return array
     */
    public function oneById(int $id, $condition = null)
    {
        $builder = new ReadQueryBuilder();
        $builder->andWhere(['id' => $id]);
        if ($condition) {
            if (is_callable($condition)) {
                call_user_func($condition, $builder);
            } else {
                $builder->andWhere($condition);
            }
        }
        return $this->fetchOne($builder);
    }

    /**
     * Возвращает запись по ассоциативному массиву условий
     * [
     *    'caption' => 'some',
     *    'description' => 'some',
     * ]
     *
     * Пример расширения запроса через $callback
     * function(ReadQueryBuilder $builder) {
     *       $builder->andWhere("object_type = :object_type", [':object_type' => 2]);
     * }
     *
     * @param array|callable $condition
     * @return array
     */
    public function oneByCondition($condition)
    {
        $builder = new ReadQueryBuilder();
        if (is_callable($condition)){
            call_user_func($condition, $builder);
        }else{
            $builder->andWhere($condition);
        }

        return $this->fetchOne($builder);
    }

    /**
     * Возвращает 1 запись
     *
     * @param BaseReadQueryBuilder $builder
     * @return array
     */
    protected function fetchOne(BaseReadQueryBuilder $builder)
    {
        $this->extendBuilder($builder);
        $builder->makeSelectSql();
        $sql = $builder->getSql();
        $params = $builder->getParams();
        $stmt = $this->buildPdoStatement($sql, $params);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Возвращает массив записей по идентификаторам
     *
     * @param array $ids
     *
     * Пример расширения запроса через $callback
     * function(ReadQueryBuilder $builder) {
     *       $builder->andWhere("object_type = :object_type", [':object_type' => 2]);
     * }
     *
     * @param array|callable $condition

     * @return array
     */
    public function allByIds(array $ids, $condition = null)
    {
        $builder = new ReadQueryBuilder();
        $builder->andInCondition('id', $ids);
        if ($condition) {
            if (is_callable($condition)) {
                call_user_func($condition, $builder);
            } else {
                $builder->andWhere($condition);
            }
        }

        return $this->fetchAll($builder);
    }

    /**
     * Возвращает массив записей по ассоциативному массиву условий
     * [
     *    'caption' => 'some',
     *    'description' => 'some',
     * ]
     *
     * Пример расширения запроса через $callback
     * function(ReadQueryBuilder $builder) {
     *       $builder->andWhere("object_type = :object_type", [':object_type' => 2]);
     * }
     *
     * @param array|ReadCondition|callable $condition
     * @return array
     */
    public function allByCondition($condition)
    {
        $builder = new ReadQueryBuilder();
        if (is_callable($condition)) {
            call_user_func($condition, $builder);
        }else if ($condition instanceof ReadCondition){
            $builder->applyReadQuery($condition);
        }else{
            $builder->andWhere($condition);
        }

        return $this->fetchAll($builder);
    }

    /**
     * Возвращает массив записей
     *
     * @param BaseReadQueryBuilder $builder
     * @return array
     * @throws DBALExceptionAlias
     */
    protected function fetchAll(BaseReadQueryBuilder $builder)
    {
        $this->extendBuilder($builder);
        $builder->makeSelectSql();
        $sql = $builder->getSql();
        $params = $builder->getParams();
        $stmt = $this->buildPdoStatement($sql, $params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Возвращает PdoStatement
     *
     * @param $sql
     * @param $params
     * @return Statement
     */
    private function buildPdoStatement(string $sql, array $params = [])
    {
        $stmt = $this->getConnection()->prepare($sql);
        foreach ($params as $name => $value){
            $stmt->bindValue($name, $value);
        }
        $stmt->execute();

        return $stmt;
    }

    /**
     * Расширяет билдер
     * Если используется ReadQueryBuilder добавляет таблицу
     *
     * @param $builder
     */
    private function extendBuilder($builder)
    {
        if ($builder instanceof ReadQueryBuilder) {
            $builder->from($this->getTableName());
        }
    }
}

