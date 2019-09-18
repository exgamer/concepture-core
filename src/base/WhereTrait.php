<?php
namespace concepture\core\base;

use concepture\core\enum\DbQueryEnum;

/**
 * Trait WhereTrait
 *
 * @author citizenze/kamaelkz
 */
trait WhereTrait
{
    protected $where = [];

    public function orWhere($sqlCondition, $params = [])
    {
        $this->where($sqlCondition, DbQueryEnum::OPERATOR_OR, $params);

        return $this;
    }

    public function andWhere($sqlCondition, $params = [])
    {
        $this->where($sqlCondition, DbQueryEnum::OPERATOR_AND, $params);

        return $this;
    }

    protected function where($sqlCondition, $operator, $params = [])
    {
        $this->where[] = [
            $operator,
            $sqlCondition
        ];
        foreach ($params as $key=>$value){
            $this->params[$key] = $value;
        }
    }

    public function andInCondition($field, $data)
    {
        $this->inCondition($field, $data, DbQueryEnum::OPERATOR_AND );

        return $this;
    }

    public function orInCondition($field, $data)
    {
        $this->inCondition($field, $data, DbQueryEnum::OPERATOR_OR );

        return $this;
    }

    protected function inCondition($field, $data, $operator)
    {
        $sql = "";
        $sql .= "{$field} IN (";
        $parts = [];
        $params = [];
        foreach ($data as $key => $d){
            $parts[] = ":".$key.$field;
            $params[":".$key.$field] = $d;
        }
        $sql .= implode(",",  $parts);
        $sql .= ")";

        $this->where($sql, $operator, $params);

        return $this;
    }

    protected function makeWhereSql()
    {
        $sql = " ";
        if (! empty($this->where)){
            $where = [];
            foreach ($this->where as $key => $where){
                if ($key == 0){
                    $where[] = $where[1];
                    continue;
                }
                $where[] = $where[0] . " " . $where[1];
            }
            $sql .= " WHERE " . implode(" ", $where);
        }

        return $sql;
    }
}

