<?php
namespace concepture\core\base;

use concepture\core\base\SearchDto;

interface ReadInterface
{
    public function one(SearchDto $searchDto = null);

    public function all(SearchDto $searchDto = null);
}
