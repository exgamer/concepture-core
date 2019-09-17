<?php
namespace concepture\core\base;

/**
 * BaseReadQueryBuilder
 *
 * @author citizenzer <exgamer@live.ru>
 */
class BaseReadQueryBuilder extends DbQueryBuilder
{
    public function makeSelectSql()
    {

        return $this;
    }
}
