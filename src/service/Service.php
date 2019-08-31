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

    public function insert(&$data)
    {
        $this->beforeInsert($data);
        $dto = $this->getDto();
        $dto->load($data);
        if ($dto->hasErrors()){

        }
        $id = $this->getRepository()->insert($dto->getData());
        $this->afterInsert($data);

        return $id;
    }

    public function beforeInsert(&$data){}
    public function afterInsert(&$data){}

    public function update($data, $condition)
    {
        $this->beforeUpdate($data, $condition);
        $dto = $this->getDto();
        $dto->load($data);
        if ($dto->hasErrors()){

        }
        $this->getRepository()->update($dto->getDataForUpdate(), $condition);
        $this->afterUpdate($data, $condition);
    }

    public function beforeUpdate(&$data, $condition){}
    public function afterUpdate(&$data, $condition){}


    public function delete($condition)
    {
        $this->beforeDelete($condition);
        $this->getRepository()->delete($condition);
        $this->afterDelete($condition);
    }

    public function beforeDelete($condition){}
    public function afterDelete($condition){}

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
    public function getDtoClass()
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