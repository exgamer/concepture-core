<?php
namespace concepture\core\helper;

use concepture\core\helpers\StringHelper;

/**
 * Вспомогательный класс
 *
 * @author CitizenZet <exgamer@live.ru>
 */
class ClassHelper
{

    /**
     * Получить из неимспейса основной путь
     * @param $className
     * @return string
     */
    public static function getNamespace($className)
    {
        $array = explode('\\', $className);
        $offset = count($array) - 2;
        array_splice($array, $offset);

        return  implode("\\",$array);
    }

    /**
     * Возвращает имя сущности из неимспеиса
     * @param $className
     * @param string $classPostfix
     * @return mixed
     */
    public static function getName($className, $classPostfix = "")
    {
        $baseName = StringHelper::basename($className);

        return  str_replace($classPostfix,"" ,$baseName);
    }

}