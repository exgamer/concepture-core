<?php
namespace concepture\core\traits;


use concepture\core\base\ModifyQueryBuilder;

trait StorageModifyMethodsTrait
{
    public function insert($params)
    {
        $this->getConnection()->beginTransaction();
        try {
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
            $stmt->execute();
            $stmt = $this->getConnection()->query('SELECT last_insert_id()');
            $result = $stmt->fetchColumn();
            $this->getConnection()->commit();

            return $result;
        }catch (\Exception $e){
            $this->getConnection()->rollBack();

            throw $e;
        }
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

    public function deleteById($id)
    {
        $condition = [
            'id' => $id
        ];

        return $this->delete($condition);
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
}

