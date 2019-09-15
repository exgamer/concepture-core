<?php
namespace concepture\core\service;

use concepture\core\base\Component;
use concepture\core\base\DataReadConfig;
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
        $id = $this->getStorage()->insert($dto->getDataForCreate());
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
        $this->getStorage()->update($dto->getDataForUpdate(), $condition);
        $this->afterUpdate($data, $condition);
    }

    protected function beforeUpdate(&$data, $condition){}
    protected function afterUpdate(&$data, $condition){}

    public function delete($condition)
    {
        $this->beforeDelete($condition);
        $dto = $this->getDto();
        $dto->load($condition);
        if ($dto->hasErrors()){
            return $dto->getErrors();
        }
        $this->getStorage()->delete($dto->getData());
        $this->afterDelete($condition);
    }

    protected function beforeDelete($condition){}
    protected function afterDelete($condition){}

    public function one($condition, $storageMethod = "one")
    {
        return $this->read($condition, $storageMethod);
    }

    public function all($condition, DataReadConfig $config, $storageMethod = "all")
    {
        return $this->read($condition, $storageMethod, $config);
    }

    public function read($condition, $storageMethod, DataReadConfig $config = null)
    {
        $dto = $this->getDto();
        $dto->read();
        $dto->load($condition);
        if ($dto->hasErrors()){
            return $dto->getErrors();
        }
        return $this->getStorage()->{$storageMethod}($dto->getDataForRead(), $config);
    }

    public function __call($method, $parameters)
    {
        $storage = $this->getStorage();
        if (method_exists($storage,$method))
        {
            return call_user_func_array([$storage, $method], $parameters);
        }

        parent::__call($method, $parameters);
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