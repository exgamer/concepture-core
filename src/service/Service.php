<?php
namespace concepture\core\service;

use concepture\core\base\Component;
use concepture\core\helpers\ClassHelper;
use concepture\core\helpers\ContainerHelper;
use concepture\core\storage\Storage;
use concepture\core\traits\ServiceReadMethodsTrait;
use concepture\core\traits\ServiceModifyMethodsTrait;
use concepture\core\traits\LoggerTrait;

abstract class Service extends Component
{
    use ServiceModifyMethodsTrait;
    use ServiceReadMethodsTrait;
    use LoggerTrait;

    private $_storage;
    public $storageDir = null;
    public $storageConfig = [];

    public function __call($method, $parameters)
    {
        if (method_exists($this,$method))
        {
            parent::__call($method, $parameters);
        }
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

    protected function getStorageClass($storagePostfix = "Storage", $folder = "storage")
    {
        $className = get_class($this);
        $name = ClassHelper::getName($className, ClassHelper::getName(self::class));
        $nameSpace = ClassHelper::getNamespace($className);
        $extPath = "";
        if ($this->storageDir){
            $extPath .= $this->storageDir.'\\';
        }

        return  $nameSpace.'\\'.$folder.'\\'.$extPath.$name.$storagePostfix;
    }
}