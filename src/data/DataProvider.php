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
    private $filterClass;
    private $queryParams = [];


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
     * Устанавливаем кол-во элементов
     *
     * @param integer $value
     */
    protected function setTotalCount(int $value)
    {
        $this->totalCount = $value;
    }

    /**
     * Возвращает текущую страницу
     *
     * @return int
     */
    protected function getPage(): int
    {
        return $this->page;
    }

    /**
     * Устанавливает текущую старницу
     *
     * @param int $page
     */
    protected function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * Возвращает количество элементов на странице
     *
     * @return int
     */
    protected function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Устанавливает колчесвто элементов на странице
     *
     * @param int $pageSize
     */
    protected function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    /**
     * Устанавливает данные
     *
     * @param array $data
     */
    protected function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    protected function getFilterClass()
    {
        return $this->filterClass;
    }

    /**
     * @param string $filterClass
     */
    protected function setFilterClass(string $filterClass): void
    {
        $this->filterClass = $filterClass;
    }

    /**
     * @return array
     */
    protected function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @param array $queryParams
     */
    protected function setQueryParams(array $queryParams): void
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
}