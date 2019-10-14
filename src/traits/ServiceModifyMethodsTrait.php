<?php
namespace concepture\core\traits;

use concepture\core\base\DataValidationErrors;
use concepture\core\base\Dto;
use concepture\core\helpers\ClassHelper;

/**
 * Треит с методами сервиса для модификации данных
 *
 * Trait ServiceModifyMethodsTrait
 * @package concepture\core\traits
 *
 * @author citizenzet <exgamer@live.ru>
 */
trait ServiceModifyMethodsTrait
{
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

    /**
     * Обновление
     *
     * @param $id
     * @param $data
     *
     * @return DataValidationErrors | null
     */
    public function update(int $id, array  $data)
    {
        $oldData = $this->getOldData(['id' => $id]);
        $changedData = $this->getChangedData($data, $oldData);
        $data = $this->validateUpdateData($data);
        if ($data instanceof DataValidationErrors){

            return $data;
        }
        $this->preUpdate($id,$data, $oldData, $changedData);
        $this->preUpdateExternal($id, $data, $oldData, $changedData);
        $this->getStorage()->updateById($id, $data);
        $this->postUpdate($id, $data, $oldData, $changedData);
        $this->postUpdateExternal($id, $data, $oldData, $changedData);
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

    /**
     * Метод для дополнительной обработки текущей сущности перед обновлением
     *
     * @param int $id
     * @param array $data
     * @param array $oldData
     * @param array $changedData
     */
    public function preUpdate(int $id, array  &$data,  array $oldData = [], array $changedData = []){}

    /**
     * Метод для дополнительной обработки текущей сущности после обновления
     *
     * @param int $id
     * @param array $data
     * @param array $oldData
     * @param array $changedData
     */
    public function postUpdate(int $id, array  &$data,  array $oldData = [], array $changedData = []){}

    /**
     * Метод для дополнительной обработки связанных сущностей перед обновлением
     *
     * @param int $id
     * @param array $data
     * @param array $oldData
     * @param array $changedData
     */
    public function preUpdateExternal(int $id, array  &$data,  array $oldData = [], array $changedData = []){}

    /**
     * Метод для дополнительной обработки связанных сущностей после обновления
     *
     * @param int $id
     * @param array $data
     * @param array $oldData
     * @param array $changedData
     */
    public function postUpdateExternal(int $id, array  &$data,  array $oldData = [], array $changedData = []){}

    /**
     * Удаление
     *
     * @param int $id
     */
    public function remove(int $id)
    {
        $this->preRemove($id);
        $this->preRemoveExternal($id);
        $this->getStorage()->removeById($id);
        $this->postRemove($id);
        $this->postRemoveExternal($id);
    }

    /**
     * Метод для дополнительной обработки текущей сущности перед удалением
     *
     * @param int $id
     */
    public function preRemove(int $id){}

    /**
     * Метод для дополнительной обработки текущей сущности после удаления
     *
     * @param int $id
     */
    public function postRemove(int $id){}

    /**
     * Метод для дополнительной обработки связанных сущностей перед удалением
     *
     * @param int $id
     */
    public function preRemoveExternal(int $id){}

    /**
     * Метод для дополнительной обработки связанных сущностей после удаления
     *
     * @param int $id
     */
    public function postRemoveExternal(int $id){}


    /**
     * @return Dto
     */
    public function getDto()
    {
        $dtoClass = $this->getDtoClass();

        return new $dtoClass();
    }


    /**
     * Возвращает старую запись
     *
     * @param array $condition
     * @return array
     */
    protected function getOldData(array $condition)
    {

        return [];
    }

    /**
     * Возвращает массив с измененными данными
     *
     * @param array $data
     * @param array $oldData
     *
     * Возвращает массив где значение массив данных где 1 элемент старове значение второй новое
     * [
     *      0 => 1,
     *      1 => 2
     * ]
     *
     * @return array
     */
    protected function getChangedData(array $data, array $oldData)
    {
        $changedData = [];
        foreach ($oldData as $attr=>$value){
            if (! isset($data[$attr])){
                continue;
            }
            if ($value == $data[$attr]){
                continue;
            }
            $changedData[$attr] = [
                $value,
                $data[$attr]
            ];
        }

        return $changedData;
    }

    /**
     * Получить класс DTO
     * @param string $folder
     * @return string
     */
    protected function getDtoClass($folder = "dto")
    {
        $className = get_class($this);
        $name = ClassHelper::getName($className, ClassHelper::getName(self::class));
        $nameSpace = ClassHelper::getNamespace($className);

        return  $nameSpace."\\".$folder."\\".$name."Dto";
    }
}

