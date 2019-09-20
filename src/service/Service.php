<?php
namespace concepture\core\service;

use concepture\core\base\Component;
use concepture\core\base\DataValidationErrors;
use concepture\core\base\Dto;
use concepture\core\helpers\ClassHelper;
use concepture\core\helpers\ContainerHelper;
use concepture\core\storage\DbStorage;

abstract class Service extends Component
{
    private $_storage;
    public $storageDir = null;
    public $storageConfig = [];

    public function insert($data)
    {
        $data = $this->validateInsertData($data);
        if ($data instanceof DataValidationErrors){

            return $data;
        }
        $this->beforeInsert($data);
        $this->beforeInsertExternal($data);
        $id = $this->getStorage()->insert($data);
        $this->afterInsert($data);
        $this->afterInsertExternal($data);

        return $id;
    }

    protected function validateInsertData($data)
    {
        $dto = $this->getDto();
        $dto->load($data);
        if ($dto->hasErrors()){

            return $dto->getErrors();
        }

        return $dto->getDataForCreate();
    }

    protected function beforeInsert(&$data){}
    protected function afterInsert(&$data){}
    protected function beforeInsertExternal(&$data){}
    protected function afterInsertExternal(&$data){}

    public function update($data, $condition)
    {
        $data = $this->validateUpdateData($data);
        if ($data instanceof DataValidationErrors){

            return $data;
        }
        $this->beforeUpdate($data, $condition);
        $this->beforeUpdateExternal($data, $condition);
        $this->getStorage()->update($data, $condition);
        $this->afterUpdate($data, $condition);
        $this->afterUpdateExternal($data, $condition);
    }

    protected function validateUpdateData($data)
    {
        $dto = $this->getDto();
        $dto->load($data);
        if ($dto->hasErrors()){

            return $dto->getErrors();
        }

        return $dto->getDataForUpdate();
    }

    protected function beforeUpdate(&$data, $condition){}
    protected function afterUpdate(&$data, $condition){}
    protected function beforeUpdateExternal(&$data, $condition){}
    protected function afterUpdateExternal(&$data, $condition){}

    public function delete($condition)
    {
        $this->beforeDelete($condition);
        $this->beforeDeleteExternal($condition);
        $this->getStorage()->delete($condition);
        $this->afterDelete($condition);
        $this->afterDeleteExternal($condition);
    }

    protected function beforeDelete($condition){}
    protected function afterDelete($condition){}
    protected function beforeDeleteExternal($condition){}
    protected function afterDeleteExternal($condition){}

//    public function one($condition, $storageMethod = "one")
//    {
//        return $this->read($condition, $storageMethod);
//    }
//
//    public function all($condition, DataReadConfig $config, $storageMethod = "all")
//    {
//        return $this->read($condition, $storageMethod, $config);
//    }
//
//    public function read($condition, $storageMethod, DataReadConfig $config = null)
//    {
//        return $this->getStorage()->{$storageMethod}($condition, $config);
//    }

    public function __call($method, $parameters)
    {
        if (method_exists($this,$method))
        {
            return parent::__call($method, $parameters);
        }
        $storage = $this->getStorage();
        if (method_exists($storage,$method))
        {
            return call_user_func_array([$storage, $method], $parameters);
        }

        parent::__call($method, $parameters);
    }

    /**
     * @return DbStorage
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