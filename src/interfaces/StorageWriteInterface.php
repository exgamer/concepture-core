<?php
namespace concepture\core\interfaces;

interface StorageWriteInterface
{
    public function insert($params);
    public function updateById($id, $params);
    public function update($params, $condition);
    public function deleteById($id);
    public function delete($condition);
}
