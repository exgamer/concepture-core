<?php
namespace concepture\core\domain;

use concepture\core\base\Component;
use concepture\core\helpers\ArrayHelper;
use concepture\core\helpers\ContainerHelper;
use concepture\core\service\Service;

abstract class Domain extends Component
{
    private $_services;

    private function setService($name, Service $service)
    {
        $this->_services[$name] = $service;
    }

    abstract static function services();

    private function getService($key)
    {
        if (isset($this->_services[$key])){
            return $this->_services[$key];
        }
        $services = static::services();
        $config = ArrayHelper::getValue($services, $key);
        if ($config === null){
            return $config;
        }
        $service = ContainerHelper::createObject($config);
        if ($service === null){
            return $service;
        }
        $this->setService($key, $service);

        return $service;
    }

    public function __get($name)
    {
        $service = $this->getService($name);
        if ($service){
            return $service;
        }

        return parent::__get($name);
    }
}