<?php
namespace concepture\core\data;

use concepture\core\base\Component;

/**
 * Базовый класс для постраничного получения данных из хранилища
 *
 * Class DataProvider
 * @author citizenzet <exgamer@live.ru>
 */
abstract class DataProvider extends Component
{
    private $data = [];
    private $totalCount = 0;
    private $page = 1;
    private $pageSize = 10;
    private $queryParams = [];
    protected $dataReceiverConfig = [];
    private $filterClass = "";

    public function init()
    {
        parent::init();
        $this->execute();
    }

    /**
     * executes data provider
     */
    protected function execute()
    {
        $data = $this->receiveData();
        $this->setData($data);
    }

    /**
     * @return string
     */
    public function getFilterClass()
    {
        return $this->filterClass;
    }

    /**
     * @param string $filterClass
     */
    public function setFilterClass(string $filterClass): void
    {
        $this->filterClass = $filterClass;
    }

    /**
     * @return array
     */
    public function getDataReceiverConfig(): array
    {
        return $this->dataReceiverConfig;
    }

    /**
     * @param array $dataReceiverConfig
     */
    public function setDataReceiverConfig(array $dataReceiverConfig): void
    {
        $this->dataReceiverConfig = $dataReceiverConfig;
    }

    /**
     * Устанавливаем кол-во элементов
     *
     * @param integer $value
     */
    public function setTotalCount(int $value)
    {
        $this->totalCount = $value;
    }

    /**
     * Возвращает текущую страницу
     *
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Устанавливает текущую старницу
     *
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * Возвращает количество элементов на странице
     *
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Устанавливает колчесвто элементов на странице
     *
     * @param int $pageSize
     */
    public function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    /**
     * Устанавливает данные
     *
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @param array $queryParams
     */
    public function setQueryParams(array $queryParams): void
    {
        $this->queryParams = $queryParams;
    }

    /**
     * Возвращает данные
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * returns total rows count
     *
     * @return integer
     */
    public function getTotalCount() : int
    {
        return $this->totalCount;
    }

    /**
     * Возвращает массив данных из хранилища
     *
     * @return array
     */
    protected abstract function receiveData() : array;

    /**
     * @return DataReceiver
     */
    protected abstract function getDataReceiverClass();

    protected function getDataReceiver()
    {
        $dataReceiverClass = $this->getDataReceiverClass();
        $dataReceiverConfig = $this->getDataReceiverConfig();
        if (! empty($dataReceiverConfig) && isset($dataReceiverConfig['class'])){
            $dataReceiverClass = $dataReceiverConfig['class'];
            unset($dataReceiverConfig['class']);
        }

        return new $dataReceiverClass([
            "dataProvider" => $this,
            "filterClass" => $this->getFilterClass(),
        ]);
    }
}