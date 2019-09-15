<?php
namespace concepture\core\base;

use concepture\core\enum\DbQueryEnum;

/**
 * DbConditionBuilder
 *
 * @author citizenzer <exgamer@live.ru>
 */
class DbReadConditionBuilder extends DbDataReadCondition
{
    protected $where = [];
    protected $join = [];
    protected $select = [];
    protected $table;
    protected $order = null;
    protected $limit = null;
    protected $offest = null;

    public function select($items)
    {
        foreach ($items as $item){
            $this->select[] = $item;
        }
    }

    public function from($table)
    {
        $this->table = $table;
    }

    public function join($table, $on, $params = [])
    {
        $this->setJoin($table, $on, DbQueryEnum::JOIN, $params);
    }

    public function outerJoin($table, $on, $params = [])
    {
        $this->setJoin($table, $on, DbQueryEnum::OUTER_JOIN, $params);
    }

    public function innerJoin($table, $on, $params = [])
    {
        $this->setJoin($table, $on, DbQueryEnum::INNER_JOIN, $params);
    }

    public function rightJoin($table, $on, $params = [])
    {
        $this->setJoin($table, $on, DbQueryEnum::RIGHT_JOIN, $params);
    }

    public function leftJoin($table, $on, $params = [])
    {
        $this->setJoin($table, $on, DbQueryEnum::LEFT_JOIN, $params);
    }

    protected function setJoin($table, $on, $type = DbQueryEnum::LEFT_JOIN, $params = [])
    {
        $this->join[] = [
            $type,
            $table,
            $on
        ];
        foreach ($params as $key=>$value){
            $this->params[$key] = $value;
        }
    }

    public function orWhere($sqlCondition, $params = [])
    {
        $this->where($sqlCondition, DbQueryEnum::OPERATOR_OR, $params);
    }

    public function andWhere($sqlCondition, $params = [])
    {
        $this->where($sqlCondition, DbQueryEnum::OPERATOR_AND, $params);
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

    public function order($order)
    {
        $this->order = $order;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
    }

    public function makeFullSql()
    {
        $sql = "SELECT ";
        if (empty($this->select)){
            $sql .= "*";
        }else{
            $sql .= implode(",", $this->select);
        }
        $sql .= " FROM " . $this->table;
        $sql .= $this->makeJoinSql();
        $sql .= $this->makeWhereSql();
        if ($this->order){
            $sql .= " ORDER BY ". $this->order;
        }
        if ($this->limit){
            $sql .= " LIMIT ". $this->limit;
        }
        if ($this->offest){
            $sql .= " OFFSET ". $this->offest;
        }
    }

    public function makeJoinSql()
    {
        $sql = "";
        if (! empty($this->join)){
            $join = [];
            foreach ($this->join as $join){
                $join[] = $join[0]. " " . $join[1] . " " . $join[2];
            }
            $sql .= implode(" ", $join);
        }

        return $sql;
    }

    public function makeWhereSql()
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
