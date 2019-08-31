<?php

namespace concepture\core\base;


use concepture\core\base\Dto;

interface ModifyInterface
{
	public function insert(Dto $dto);

	public function update(Dto $dto);

	public function delete(Dto $dto);
}