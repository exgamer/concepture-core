<?php
namespace concepture\core\base;

use concepture\core\enum\DbQueryEnum;

/**
 *
 * Trait JoinTrait
 * @package Legal\Core\Db
 * @author citizenzer <exgamer@live.ru>
 */
trait JoinTrait
{
    protected $join = [];

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

    /**
     * @return array
     */
    public function getJoin(): array
    {
        return $this->join;
    }


}

