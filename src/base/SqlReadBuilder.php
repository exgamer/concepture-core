<?php
namespace concepture\core\base;

/**
 * SqlReadBuilder
 *
 * @author citizenzer <exgamer@live.ru>
 */
class SqlReadBuilder extends BaseReadQueryBuilder
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
