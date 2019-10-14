<?php
namespace concepture\core\traits;

use concepture\core\base\ReadCondition;

/**
 * Треит с методами сервиса для чтения данных
 *
 * Trait ServiceReadMethodsTrait
 * @package concepture\core\traits
 *
 * @author citizenzet <exgamer@live.ru>
 */
trait ServiceReadMethodsTrait
{
    /**
     * Возвращает одну запись по id
     * @param int $id
     *
     * [
     *    'caption' => 'some',
     *    'description' => 'some',
     * ]
     *
     * @param array $condition
     * @return array
     */
    public function oneById(int $id, array $condition = null)
    {

        return $this->getRepository()->oneById($id, $condition);
    }

    /**
     * Возвращает запись по ассоциативному массиву условий
     * [
     *    'caption' => 'some',
     *    'description' => 'some',
     * ]
     *
     * @param array $condition
     * @return array
     */
    public function oneByCondition(array $condition)
    {

        return $this->getRepository()->oneByCondition($condition);
    }

    /**
     * Возвращает массив записей по идентификаторам
     *
     * @param array $ids
     * [
     *    'caption' => 'some',
     *    'description' => 'some',
     * ]
     *
     * @param array $condition

     * @return array
     */
    public function allByIds(array $ids, array $condition = null)
    {

        return $this->getRepository()->allByIds($ids, $condition);
    }

    /**
     * Возвращает массив записей по ассоциативному массиву условий
     * [
     *    'caption' => 'some',
     *    'description' => 'some',
     * ]
     *
     *
     * @param array $condition
     * @return array
     */
    public function allByCondition(array $condition)
    {

        return $this->getRepository()->allByCondition($condition);
    }

    /**
     * Возвращает данные по ReadCondition
     *
     * @param ReadCondition $condition
     * @return array
     */
    public function allByReadCondition(ReadCondition $condition)
    {

        return $this->getRepository()->allByCondition($condition);
    }
}

