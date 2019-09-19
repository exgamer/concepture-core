<?php
namespace concepture\core\base;

use concepture\core\enum\DbQueryEnum;

/**
 * DbConditionBuilder
 *
 * @author citizenzer <exgamer@live.ru>
 */
class ReadQueryBuilder extends BaseReadQueryBuilder
{
    use WhereTrait;

    protected $join = [];
    protected $select = [];
    protected $table;
    protected $order = null;
    protected $limit = null;
    protected $offset = null;

    public function select($items)
    {
        foreach ($items as $item){
            $this->select[] = $item;
        }

        return $this;
    }

    public function from($table)
    {
        $this->table = $table;

        return $this;
    }

    public function join($table, $on, $params = [])
    {
        $this->setJoin($table, $on, DbQueryEnum::JOIN, $params);

        return $this;
    }

    public function outerJoin($table, $on, $params = [])
    {
        $this->setJoin($table, $on, DbQueryEnum::OUTER_JOIN, $params);

        return $this;
    }

    public function innerJoin($table, $on, $params = [])
    {
        $this->setJoin($table, $on, DbQueryEnum::INNER_JOIN, $params);

        return $this;
    }

    public function rightJoin($table, $on, $params = [])
    {
        $this->setJoin($table, $on, DbQueryEnum::RIGHT_JOIN, $params);

        return $this;
    }

    public function leftJoin($table, $on, $params = [])
    {
        $this->setJoin($table, $on, DbQueryEnum::LEFT_JOIN, $params);

        return $this;
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

    public function order($order)
    {
        $this->order = $order;

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = (int) $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->offset = (int) $offset;

        return $this;
    }

    public function makeSelectSql()
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
        if ($this->offset){
            $sql .= " OFFSET ". $this->offset;
        }
        $this->sql = $sql;

        return $this;
    }

    public function makeJoinSql()
    {
        $sql = "";
        if (! empty($this->join)){
            $joinArray = [];
            foreach ($this->join as $join){
                $joinArray[] = $join[0]. " " . $join[1] . " " . $join[2];
            }
            $sql .= implode(" ", $joinArray);
        }

        return $sql;
    }
}
