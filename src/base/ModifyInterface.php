<?php

namespace concepture\base;


use concepture\base\Dto;

interface ModifyInterface
{
	public function insert(Dto $dto);

	public function update(Dto $dto);

	public function delete(Dto $dto);
}