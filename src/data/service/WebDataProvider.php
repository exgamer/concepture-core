<?php
namespace concepture\core\data\service;

use concepture\core\helpers\ArrayHelper;
use concepture\core\web\pager\Pager;

/**
 * Класс для постраничного получения данных из хранилища
 *
 * Class DataProvider

 * @author citizenzet <exgamer@live.ru>
 */
class WebDataProvider extends DataProvider
{
    protected $pagerConfig = [];
    protected $pager;

    /**
     * executes data provider
     */
    protected function execute()
    {
        parent::execute();
        $this->createPager();
    }

    /**
     * @return Pager
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * @return array
     */
    public function getPagerConfig(): array
    {
        return $this->pagerConfig;
    }

    /**
     * @param array $pagerConfig
     */
    public function setPagerConfig(array $pagerConfig): void
    {
        $this->pagerConfig = $pagerConfig;
    }

    /**
     * Создание Pager
     */
    protected function createPager() : void
    {
        $pagerConfig = $this->getPagerConfig();
        $exConfig = [
            'totalCount' => $this->getTotalCount(),
            'page' => $this->getPage(),
            'pageSize' => $this->getPageSize(),
            'queryParams' => $this->getQueryParams(),
        ];
        $pagerConfig = ArrayHelper::merge($pagerConfig, $exConfig);
        $this->pager = new Pager($pagerConfig);
    }
}