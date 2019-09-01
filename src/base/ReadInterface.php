<?php
namespace concepture\core\base;

interface ReadInterface
{
    public function one(DataReadCondition $condition);

    public function all(DataReadCondition $condition, DataReadConfig $config);
}
