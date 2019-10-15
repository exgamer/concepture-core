<?php
namespace concepture\core\data;


use concepture\core\base\Component;

abstract class DataReceiver extends Component
{
    /**
     * @var DataProvider
     */
   protected $dataProvider;
   private $filterClass;

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
     * @return DataProvider
     */
    protected function getDataProvider(): DataProvider
    {
        return $this->dataProvider;
    }

    /**
     * @param DataProvider $dataProvider
     */
    protected function setDataProvider(DataProvider $dataProvider): void
    {
        $this->dataProvider = $dataProvider;
    }
   
    /**
     * Возвращает массив данных
     *
     * @return array
     */
    public abstract function receiveData();
}