<?php
namespace concepture\core\base;

interface ModifyInterface
{
	public function insert($params);

	public function update($params, $condition);

	public function delete($condition);
}