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
    use JoinTrait;
    use SelectionTrait;
    use OrderTrait;
    use LimitOffsetTrait;
    use GroupTrait;

    protected $calcRows = false;

    public function makeSelectSql()
    {
        $sql = "SELECT ";
        if ($this->calcRows){
            $sql .= " SQL_CALC_FOUND_ROWS ";
        }
        if (empty($this->select)){
            $sql .= "*";
        }else{
            $sql .= implode(",", $this->select);
        }
        $sql .= " FROM " . $this->table;
        if ($this->tableAlias){
            $sql .= " " . $this->tableAlias;
        }
        $sql .= " ". $this->makeJoinSql();
        $sql .= " ".$this->makeWhereSql();
        if ($this->group){
            $sql .= " GROUP BY ". $this->group;
        }
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
                $joinArray[] = $join[0]. " " . $join[1] . " ON " . $join[2];
            }
            $sql .= implode(" ", $joinArray);
        }

        return $sql;
    }

    /**
     * загружает ReadQuery
     *
     * @param ReadCondition $readQuery
     */
    public function applyReadQuery(ReadCondition $readQuery)
    {
        if (! empty($readQuery->getSelect())){
            $this->select = $readQuery->getSelect();
        }
        if (! empty($readQuery->getJoin())){
            $this->join = $readQuery->getJoin();
        }
        if (! empty($readQuery->getOrder())){
            $this->order = $readQuery->getOrder();
        }
        if (! empty($readQuery->getTable())){
            $this->table = $readQuery->getTable();
        }
        if (! empty($readQuery->getTableAlias())){
            $this->tableAlias = $readQuery->getTableAlias();
        }
        if (! empty($readQuery->getWhere())){
            $this->where = $readQuery->getWhere();
        }
        if (! empty($readQuery->getLimit())){
            $this->limit = $readQuery->getLimit();
        }
        if (! empty($readQuery->getOffset())){
            $this->offset = $readQuery->getOffset();
        }
        if (! empty($readQuery->getOrder())){
            $this->order = $readQuery->getOrder();
        }
        if (! empty($readQuery->getGroup())){
            $this->group = $readQuery->getGroup();
        }
        if (! empty($readQuery->getParams())){
            $this->params = $readQuery->getParams();
        }
    }
}
