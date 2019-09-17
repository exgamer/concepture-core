<?php
namespace concepture\core\base;

use concepture\core\enum\DbQueryEnum;

/**
 * DbSqlBuilder
 *
 * @author citizenzer <exgamer@live.ru>
 */
class DbSqlBuilder extends DbQueryBuilder
{
    public function setSql($sql)
    {
        $this->sql = $sql;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
}
