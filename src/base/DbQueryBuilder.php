<?php
namespace concepture\core\base;

/**
 * DbQueryBuilder
 *
 * @author citizenzer <exgamer@live.ru>
 */
abstract class DbQueryBuilder extends QueryBuilder
{
    protected $sql;
    protected $params = [];



    public function getSql()
    {
        return $this->sql;
    }

    public function getParams()
    {
        return $this->params;
    }
}
