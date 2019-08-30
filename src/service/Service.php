<?php
namespace concepture\service;

use concepture\base\Component;
use concepture\base\ModifyInterface;
use concepture\base\ReadInterface;
use concepture\core\helper\ClassHelper;
use concepture\core\helpers\ContainerHelper;
use concepture\repository\Repository;

abstract class Service extends Component implements ReadInterface, ModifyInterface
{
    private $_repository;

    protected function getRepositoryClass()
    {
        $className = get_class($this);
        $name = ClassHelper::getName($className, "Service");
        $nameSpace = ClassHelper::getNamespace($className);
        $folder = "repositories";

        return  $nameSpace.'\\'.$folder.'\\'.$name."Repository";
    }

    private function getRepository()
    {
        if ($this->_repository instanceof Repository){
            return $this->_repository;
        }
        $className = $this->getRepositoryClass();
        $repository = ContainerHelper::createObject($className);
        $this->_repository = $repository;

        return $this->_repository;
    }

    public function __get($name)
    {
        if ($name === 'repository') {
            return $this->getRepository($name);
        }

        return parent::__get($name);
    }
}