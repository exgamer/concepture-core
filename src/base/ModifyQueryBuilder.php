<?php
namespace concepture\core\base;

/**
 * ModifyQueryBuilder
 *
 * @author citizenzer <exgamer@live.ru>
 */
class ModifyQueryBuilder extends DbQueryBuilder
{
    use WhereTrait;

    protected $data = [];
    protected $table;

    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    public function makeInsertSql()
    {
        $cols = [];
        $values = [];
        foreach ($this->data as $name => $value){
            $cols[] = $name;
            $values[] = ":" . $name;
            $this->params[":" . $name] = $value;
        }
        $cols = implode(",", $cols);
        $values = implode(",", $values);
        $this->sql = "INSERT INTO {$this->table} ({$cols}) VALUES ({$values})";

        return $this;
    }

    public function makeUpdateSql()
    {
        $cols = [];
        foreach ($this->data as $name => $value){
            $cols[] = $name . " = :" . $name;
            $this->params[":" . $name] = $value;
        }
        $cols = implode(",", $cols);
        $sql = "UPDATE {$this->table}  SET {$cols} ";
        $sql .= $this->makeWhereSql();
        $this->sql = $sql;

        return $this;
    }

    public function makeDeleteSql()
    {
        $sql = "DELETE FROM {$this->table} ";
        $sql .= $this->makeWhereSql();
        $this->sql = $sql;

        return $this;
    }
}
