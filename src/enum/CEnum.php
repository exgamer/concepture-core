<?php

namespace concepture\core\enum;

use ReflectionClass;

class CEnum {

    public static function values() {
        $constants = static::all();
        $constants = array_values($constants);
        $constants = array_unique($constants);
        return $constants;
    }

    public static function keys() {
        $constants = static::all();
        $constants = array_keys($constants);
        return $constants;
    }

    public static function all() {
        $className = get_called_class();
        $class = new ReflectionClass($className);
        $constants = $class->getConstants();

        return $constants;
    }

    public static function label($value) {
        $labels = static::labels();
        if (isset($labels[$value])){
            return $labels[$value];
        }

        return "unknown";
    }

    public static function labels()
    {
        return [];
    }
}
