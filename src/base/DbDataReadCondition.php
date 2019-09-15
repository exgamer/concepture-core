<?php
namespace concepture\core\base;

/**
 * DbDataReadCondition
 *
 * @author citizenzer <exgamer@live.ru>
 */
abstract class DbDataReadCondition extends DataReadCondition
{
    protected $sql;
    protected $params = [];

    public function setSql($sql)
    {
        $this->sql = $sql;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function getParams()
    {
        return $this->params;
    }
}
