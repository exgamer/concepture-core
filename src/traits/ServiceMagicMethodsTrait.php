<?php
namespace concepture\core\traits;


use Legal\Core\Helpers\StringHelper;
use Legal\SymfonyCore\Entity\Entity;
use Exception;
use Legal\SymfonyCore\Interfaces\ICanCloneInterface;

/**
 * @deprecated  отрефакторить
 *
 * Треит содержащий методы сервиса которые использует __call
 *
 * Trait LoggerTrait
 * @package Legal\SymfonyCore\Traits
 * @author citizenzet <exgamer@live.ru>
 */
trait ServiceMagicMethodsTrait
{

    /**
     * Вызывается при обращении к методам обработки данных с передачей обьекта entity вместо массива
     *
     * @param $methodName
     * @param $parameters
     * @return mixed
     * @throws Exception
     */
    private function callDataProcessMethodsWithEntity($methodName, $parameters)
    {
        if (! in_array($methodName, ["prePersist", "postPersist", "prePersistExternal", "postPersistExternal", "preUpdate", "preUpdateExternal", "postUpdate", "postUpdateExternal"])) {
            throw new Exception("to call {$methodName}WithEntity denied");
        }
        $parameterIndex = $this->getDataParameterIndex($methodName);
        $entity = $parameters[$parameterIndex];
        if (! is_object($entity)){
            throw new Exception("to call {$methodName}WithEntity data must be an object");
        }
        if (!$entity instanceof Entity){

            throw new Exception("entity must be instance of ". Entity::class);
        }
        $dataArray = $entity->toArray();
        $parameters[$parameterIndex] = &$dataArray;
        /**
         * Для следующих методов подсовываем старые данные и измененные
         */
        if (in_array($methodName, ["preUpdate", "preUpdateExternal", "postUpdate", "postUpdateExternal"])){
            $oldData = $this->getUnitOfWork()->getOriginalEntityData($entity);
            $this->getUnitOfWork()->computeChangeSets();
            $changedData = $this->getUnitOfWork()->getEntityChangeSet($entity);
            $parameters[] = $oldData;
            $parameters[] = $changedData;
        }
        call_user_func_array([$this, $methodName], $parameters);
        $entity->loadData($parameters[$parameterIndex]);
        return $entity;
    }

    /**
     * Возвращает индекс параметра с данными в зависимости от метода
     * Только для метода callDataProcessMethodsWithEntity
     * @param $serviceMethod
     * @return int
     */
    private function getDataParameterIndex($serviceMethod)
    {
        /**
         * Для создания $data 1 параметр
         */
        if (in_array($serviceMethod, ["prePersist", "postPersist", "prePersistExternal", "postPersistExternal"])) {
            return 0;
        }
        /**
         * Для редактирования $data 2 параметр
         */
        if (in_array($serviceMethod, ["preUpdate", "preUpdateExternal", "postUpdate", "postUpdateExternal"])) {
            return 1;
        }
    }

    /**
     * Вызывается при обращении к методу копирования данных с сущностью
     *
     * @param $methodName
     * @param $parameters
     * @return mixed
     * @throws \ReflectionException
     */
    private function callDataCloneMethodWithEntity($methodName, $parameters)
    {
        if (! in_array($methodName, ["getClone"])) {
            throw new Exception("to call {$methodName}WithEntity denied");
        }
        $entity = $parameters[0];
        if (! is_object($entity)){
            throw new Exception("to call {$methodName}WithEntity data must be an object");
        }
        if (!$entity instanceof Entity){

            throw new Exception("entity must be instance of ". Entity::class);
        }
        if (!$entity instanceof ICanCloneInterface){

            throw new Exception("entity must implement ". ICanCloneInterface::class);
        }
        $excludeProps = $entity::cloneExcludeProperties();
        /**
         * Приводим к underscore
         */
        foreach ($excludeProps as &$prop){
            $prop = StringHelper::fromCamelCase($prop);
        }
        $dataArray = $entity->toArray();
        $parameters[0] = &$dataArray;
        if (! isset($parameters[1])){
            $parameters[1] = [];
        }
        $parameters[2] = $excludeProps;
        $data = call_user_func_array([$this, $methodName], $parameters);
        $entityClass = get_class($entity);
        $newEntity = new $entityClass();
        $newEntity->loadData($data);

        return $newEntity;
    }

}