<?php
namespace concepture\core\service;

use concepture\core\base\Component;
use concepture\core\base\DataReadCondition;
use concepture\core\base\Dto;
use concepture\core\helpers\ClassHelper;
use concepture\core\helpers\ContainerHelper;
use concepture\core\storage\Storage;

abstract class Service extends Component
{
    private $_storage;
    public $storageDir = null;
    public $storageConfig = [];

    public function insert(&$data)
    {
        $this->beforeInsert($data);
        $dto = $this->getDto();
        $dto->load($data);
        if ($dto->hasErrors()){
            return $dto->getErrors();
        }
        $id = $this->getStorage()->insert($dto->getData());
        $this->afterInsert($data);

        return $id;
    }

    protected function beforeInsert(&$data){}
    protected function afterInsert(&$data){}

    public function update($data, DataReadCondition $condition)
    {
        $this->beforeUpdate($data, $condition);
        $dto = $this->getDto();
        $dto->load($data);
        if ($dto->hasErrors()){
            return $dto->getErrors();
        }
        $this->getStorage()->update($dto->getDataForUpdate(), $condition);
        $this->afterUpdate($data, $condition);
    }

    protected function beforeUpdate(&$data, DataReadCondition $condition){}
    protected function afterUpdate(&$data, DataReadCondition $condition){}


    public function delete(DataReadCondition $condition)
    {
        $this->beforeDelete($condition);
        $this->getStorage()->delete($condition);
        $this->afterDelete($condition);
    }

    protected function beforeDelete(DataReadCondition $condition){}
    protected function afterDelete(DataReadCondition $condition){}

    protected function getStorageClass($folder = "storage")
    {
        $className = get_class($this);
        $name = ClassHelper::getName($className, "Service");
        $nameSpace = ClassHelper::getNamespace($className);
        $extPath = "";
        if ($this->storageDir){
            $extPath .= $this->storageDir.'\\';
        }

        return  $nameSpace.'\\'.$folder.'\\'.$extPath.$name."Storage";
    }

    /**
     * @return Storage
     */
    protected function getStorage()
    {
        if ($this->_storage instanceof Storage){
            return $this->_storage;
        }
        $className = $this->getStorageClass();
        $storageConfig = [
            $className,
            $this->storageConfig
        ];
        $storage = ContainerHelper::createObject($storageConfig);
        $this->_storage = $storage;

        return $this->_storage;
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
     * @param string $folder
     * @return string
     */
    protected function getDtoClass($folder = "dto")
    {
        $className = get_class($this);
        $name = ClassHelper::getName($className, "Service");
        $nameSpace = ClassHelper::getNamespace($className);

        return  $nameSpace."\\".$folder."\\".$name."Dto";
    }
}