<?php
namespace concepture\core\base;

/**
 *
 * Trait SelectionTrait
 * @package Legal\Core\Db
 * @author citizenzer <exgamer@live.ru>
 */
trait SelectionTrait
{
    protected $table;
    protected $select = [];
    protected $tableAlias = null;

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

    public function alias($tableAlias)
    {
        $this->tableAlias = $tableAlias;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return array
     */
    public function getSelect(): array
    {
        return $this->select;
    }

    /**
     * @return null
     */
    public function getTableAlias()
    {
        return $this->tableAlias;
    }


}

