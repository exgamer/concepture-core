<?php
namespace concepture\core\base;

interface ReadInterface
{
    public function one(SearchDto $searchDto = null);

    public function all(SearchDto $searchDto = null);
}
