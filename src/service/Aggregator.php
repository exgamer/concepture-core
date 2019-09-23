<?php
namespace concepture\core\service;

use concepture\core\base\TransactionInterface;
use concepture\core\helpers\ArrayHelper;
use concepture\core\helpers\ClassHelper;
use concepture\core\helpers\ContainerHelper;
use Exception;

abstract class Aggregator extends Service
{
    protected function getStorageClass($storagePostfix = "Aggregator", $folder = "storage")
    {
        return parent::getStorageClass($storagePostfix, $folder);
    }
}