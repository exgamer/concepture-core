<?php
namespace concepture\core\interfaces;

interface StorageReadInterface
{
    public function oneById(int $id, $condition = null);
    public function oneByCondition($condition);
    public function allByIds(array $ids, $condition = null);
    public function allByCondition($condition);
}
