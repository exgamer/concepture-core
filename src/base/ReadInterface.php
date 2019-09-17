<?php
namespace concepture\core\base;

interface ReadInterface
{
    public function one(BaseReadQueryBuilder $builder);

    public function all(BaseReadQueryBuilder $builder);
}
