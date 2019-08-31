<?php
namespace concepture\core\service;

use concepture\core\base\Component;
use concepture\core\base\Dto;
use concepture\core\helper\ClassHelper;
use concepture\core\helpers\ContainerHelper;
use concepture\core\repository\Repository;

abstract class Service extends Component
{
    private $_repository;

    public function insert(Dto $dto)
    {
        return $this->getRepository()->insert($dto->getData());
    }

    public function update(Dto $dto, $condition)
    {
        return $this->getRepository()->update($dto->getDataForUpdate(), $condition);
    }

    public function delete($condition)
    {
        return $this->getRepository()->delete($condition);
    }

    protected function getRepositoryClass($folder = "repositories")
    {
        $className = get_class($this);
        $name = ClassHelper::getName($className, "Service");
        $nameSpace = ClassHelper::getNamespace($className);

        return  $nameSpace.'\\'.$folder.'\\'.$name."Repository";
    }

    /**
     * @return Repository
     */
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
            return $this->getRepository();
        }

        return parent::__get($name);
    }
}