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


    public function makeCreateTableSql()
    {
        $cols = [];
        foreach ($this->data as $name => $value){
            if ( is_int($name) ) {
                $cols[] = $value;
                continue;
            }
            $columnName = $name;
            $options = "";
            if (is_array($value)){
                $options .= " " . $value[0];
                $options .= " " . $value[1];
            }else{
                $options = $value;
            }
            $cols[] = $columnName . " ". $options;
        }
        $cols = implode(",", $cols);
        $sql = "CREATE TABLE {$this->table} ({$cols})";
        $sql .= $this->tableConfig();
        $this->sql = $sql;

        return $this;
    }

    public function makeDropTableSql()
    {
        $sql = "DROP TABLE {$this->table}";
        $this->sql = $sql;

        return $this;
    }

    public function makeTruncateTableSql()
    {
        $sql = "TRUNCATE TABLE {$this->table}";
        $this->sql = $sql;

        return $this;
    }

    public function makeRenameTableSql($name)
    {
        $sql = "RENAME TABLE {$this->table} TO {$name}";
        $this->sql = $sql;

        return $this;
    }

    public function makeAddColumnSql($name, $type, $options = "")
    {
        $sql = "ALTER TABLE {$this->table} ADD {$name} {$type} {$options}";
        $this->sql = $sql;

        return $this;
    }


    public function makeDropColumnSql($name)
    {
        $sql = "ALTER TABLE {$this->table} DROP COLUMN {$name}";
        $this->sql = $sql;

        return $this;
    }


    public function makeRenameColumnSql($name, $new_name)
    {
        $sql = "ALTER TABLE {$this->table} RENAME COLUMN {$name} TO {$new_name}";
        $this->sql = $sql;

        return $this;
    }

    public function makeModifyColumnSql($name, $type)
    {
        $sql = "ALTER TABLE {$this->table} MODIFY {$name} TO {$type}";
        $this->sql = $sql;

        return $this;
    }

    public function makeRenameAndModifyColumnSql($name, $new_name, $type)
    {
        $sql = "ALTER TABLE {$this->table} CHANGE {$name}  {$new_name} {$type}";
        $this->sql = $sql;

        return $this;
    }

    public function makeCreateIndexSql($name, $columns, $type = "")
    {
        if (!is_array($columns)){
            $columns = [$columns];
        }
        $columns = implode(",", $columns);
        $sql = "CREATE {$type} INDEX {$name} ON {$this->table} ({$columns})";
        $this->sql = $sql;

        return $this;
    }

    public function makeDropIndexSql($name)
    {
        $sql = "DROP INDEX {$name} ON {$this->table}";
        $this->sql = $sql;

        return $this;
    }

    public function tableConfig()
    {
        return " DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB";
    }
}
