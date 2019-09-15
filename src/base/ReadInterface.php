<?php
namespace concepture\core\base;

interface ReadInterface
{
    public function one($condition, DataReadConfig $config = null);

    public function all($condition, DataReadConfig $config);
}
