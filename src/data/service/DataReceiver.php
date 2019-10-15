<?php
namespace concepture\core\data\service;

use concepture\core\data\DataReceiver as Base;
use concepture\core\base\ReadCondition;

class DataReceiver extends Base
{
    /**
     * Возвращает массив данных
     *
     * @return array
     */
    public function receiveData() : array
    {
        $readCondition = $this->getReadCondition();
        $totalCountReadCondition = clone $readCondition;
        $service = $this->getDataProvider()->getService();
        $serviceMethod = "allByReadCondition";
        /*
         * Получаем общее количество записей
         */
        $totalCountReadCondition->select(["COUNT(*) as total"]);
        $totalCount = $service->{$serviceMethod}($totalCountReadCondition);
        $this->getDataProvider()->setTotalCount($totalCount[0]['total']);
        /*
         * Возвращает select выборки и запрашиваем данные с учетом ограничений
         */
        $readCondition->limit($this->getDataProvider()->getPageSize());
        $pageSize = $this->getDataProvider()->getPageSize();
        $page = $this->getDataProvider()->getPage();
        $offset = $pageSize * (int) ($page-1);
        $readCondition->offset($offset);

        return $service->{$serviceMethod}($readCondition);
    }

    /**
     * Возвращает условие выборки
     *
     * @return ReadCondition
     */
    protected function getReadCondition()
    {
        $filterClass = $this->getFilterClass();
        if (! $filterClass) {
            return new ReadCondition();
        }
        $filter = new $filterClass(['params' => $this->getDataProvider()->getQueryParams()]);

        return $filter->getReadCondition();
    }
}