<?php
namespace concepture\base;

use concepture\base\SearchDto;

interface ReadInterface
{
    public function one(SearchDto $searchDto = null);

    public function all(SearchDto $searchDto = null);
}
