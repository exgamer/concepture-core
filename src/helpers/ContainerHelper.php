<?php

namespace concepture\core\helpers;

class ContainerHelper
{
    public static function createObject($config)
    {
        $className = "";
        $arguments = [];
        if (is_string($config)){
            $className = $config;
        }
        if (is_array($config)){
            $className = ArrayHelper::getValue($config, "class");
            $arguments = ArrayHelper::getValue($config, "arguments");
        }
        $reflector = new \ReflectionClass($className);

        return $reflector->newInstanceArgs($arguments);
    }

}