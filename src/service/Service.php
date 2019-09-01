<?php
namespace concepture\core\service;

use concepture\core\base\Component;
use concepture\core\base\Dto;
use concepture\core\helpers\ClassHelper;
use concepture\core\helpers\ContainerHelper;
use concepture\core\repository\Repository;

abstract class Service extends Component
{
    private $_repository;

    public function insert(&$data)
    {
        $this->beforeInsert($data);
        $dto = $this->getDto();
        $dto->load($data);
        if ($dto->hasErrors()){
            return $dto->getErrors();
        }
        $id = $this->getRepository()->insert($dto->getData());
        $this->afterInsert($data);

        return $id;
    }

    protected function beforeInsert(&$data){}
    protected function afterInsert(&$data){}

    public function update($data, $condition)
    {
        $this->beforeUpdate($data, $condition);
        $dto = $this->getDto();
        $dto->load($data);
        if ($dto->hasErrors()){
            return $dto->getErrors();
        }
        $this->getRepository()->update($dto->getDataForUpdate(), $condition);
        $this->afterUpdate($data, $condition);
    }

    protected function beforeUpdate(&$data, $condition){}
    protected function afterUpdate(&$data, $condition){}


    public function delete($condition)
    {
        $this->beforeDelete($condition);
        $this->getRepository()->delete($condition);
        $this->afterDelete($condition);
    }

    protected function beforeDelete($condition){}
    protected function afterDelete($condition){}

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
    protected function getRepository()
    {
        if ($this->_repository instanceof Repository){
            return $this->_repository;
        }
        $className = $this->getRepositoryClass();
        $repository = ContainerHelper::createObject($className);
        $this->_repository = $repository;

        return $this->_repository;
    }

    /**
     * @return Dto
     */
    public function getDto()
    {
        $dtoClass = $this->getDtoClass();

        return new $dtoClass();
    }

    /**
     * Получить класс DTO
     * @return string
     */
    protected function getDtoClass()
    {
        $className = get_class($this);
        $name = ClassHelper::getName($className, "Service");
        $nameSpace = ClassHelper::getNamespace($className);

        return  $nameSpace."\\"."dto\\".$name."Dto";
    }

    public function __get($name)
    {
        if ($name === 'repository') {

            return $this->getRepository();
        }

        return parent::__get($name);
    }
}