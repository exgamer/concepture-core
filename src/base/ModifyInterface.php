<?php
namespace concepture\core\base;

interface ModifyInterface
{
	public function insert($params);

	public function update($params, DataReadCondition $condition);

	public function delete(DataReadCondition $condition);
}