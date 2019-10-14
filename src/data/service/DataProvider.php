<?php
namespace concepture\core\data\service;

use concepture\core\data\DataProvider as Base;
use concepture\core\service\Service;
use concepture\core\base\ReadCondition;

/**
 * Класс для постраничного получения данных из хранилища
 *
 * Class DataProvider
 * @package Legal\SymfonyCore\Data
 * @author citizenzet <exgamer@live.ru>
 */
class DataProvider extends Base
{
    protected $service;
    protected $serviceMethod = "allByReadCondition";

    /**
     * Возвращает сервис
     *
     * @return Service
     */
    protected function getService() : Service
    {
        return $this->service;
    }

    /**
     * Устанавливает сервис
     *
     * @param Service $service
     */
    protected function setService(Service $service): void
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getServiceMethod(): string
    {
        return $this->serviceMethod;
    }

    /**
     * @param string $serviceMethod
     */
    public function setServiceMethod(string $serviceMethod): void
    {
        $this->serviceMethod = $serviceMethod;
    }

    /**
     * Возвращает массив данных
     *
     * @return array
     */
    protected function receiveData() : array
    {
        $readCondition = $this->getReadCondition();
        $totalCountReadCondition = clone $readCondition;
        $service = $this->getService();
        $serviceMethod = $this->getServiceMethod();
        /*
         * Получаем общее количество записей
         */
        $totalCountReadCondition->select(["COUNT(*) as total"]);
        $totalCount = $service->{$serviceMethod}($totalCountReadCondition);
        $this->setTotalCount($totalCount[0]['total']);
        /*
         * Возвращает select выборки и запрашиваем данные с учетом ограничений
         */
        $readCondition->limit($this->getPageSize());
        $pageSize = $this->getPageSize();
        $page = $this->getPage();
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
        $filter = new $filterClass(['params' => $this->getQueryParams()]);

        return $filter->getReadCondition();
    }
}