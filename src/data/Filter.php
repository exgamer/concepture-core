<?php
namespace concepture\core\data;

use concepture\core\base\Component;
use concepture\core\base\ReadCondition;

/**
 * Класс для настройки выборок
 *
 * Class Filter

 * @author citizenzet <exgamer@live.ru>
 */
abstract class Filter extends Component
{
    private $readCondition;
    private $params;

    public function init()
    {
        parent::init();
        $this->readCondition = new ReadCondition();
        $this->apply($this->getParams(), $this->readCondition);
    }

    /**
     * Возвращает параметры запроса
     *
     * @return array
     */
    protected function getParams() : array
    {
        return $this->params;
    }

    /**
     * Устанавливает параметры запроса
     *
     * @param array $params
     */
    protected function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * Возвращает условия выборки
     *
     * @return ReadCondition
     */
    public function getReadCondition()
    {
        return$this->readCondition;
    }

    /**
     * Метод для настройки фильтра
     *
     * @param array $params
     * @param ReadCondition $readCondition
     * @return mixed
     */
    protected abstract function apply(array $params, ReadCondition $readCondition);
}