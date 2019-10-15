<?php
namespace concepture\core\data\service;

use concepture\core\data\DataProvider as Base;
use concepture\core\service\Service;

/**
 * Класс для постраничного получения данных из хранилища
 *
 * Class DataProvider

 * @author citizenzet <exgamer@live.ru>
 */
class DataProvider extends Base
{
    protected $service;


    /**
     * Возвращает сервис
     *
     * @return Service
     */
    public function getService() : Service
    {
        return $this->service;
    }

    /**
     * Устанавливает сервис
     *
     * @param Service $service
     */
    public function setService(Service $service): void
    {
        $this->service = $service;
    }

    /**
     * Возвращает массив данных
     *
     * @return array
     */
    protected function receiveData() : array
    {

        return $this->getDataReceiver()->receiveData();
    }

    protected function getDataReceiverClass()
    {
        return DataReceiver::class;
    }
}