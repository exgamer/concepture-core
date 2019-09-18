<?php
namespace concepture\core\base;

interface ReadInterface
{
    public function one($condition);

    public function all($condition, DataReadConfig $config);
}
