Постраничное получение данных с фильтрацией
==================================

[Назад](../index.md "Необязательная подсказка")

----
Для случаев когда нужно постранично считать данные можно использовать *Legal\SymfonyCore\Data\WebDataProvider*


Из WebDataProvider можно получить пагинатор который вернет массив для генерации спсика ссылок на страницы

параметр ['pagerConfig']['urlGenCallback'] позволяет сгенерить ссылку для страниц

параметры filterClass и queryParams необязательны если не нужна фильтрация данных
   
```php
<?php

        $dp = new WebDataProvider([
            'filterClass' =>  CasinoLocalizationFilter::class,
            'queryParams' =>  $request->query->all(),
            'service' => $this->getCasinoLocalizationService(),
            'page' => 1,
            'pageSize' => 2,
            'pagerConfig' => [
                'shownPagesCount' => 100,
                'route' => $request->get("_route"),
                'urlGenCallback' => function($route, $queryParams){

                    return $this->generateUrl($route, $queryParams);
                }
            ]
        ]);
        /*
        * Получить данные
        */
        $dp->getData();
        /*
        * Получить массив для построения пагинатора
        */
        $pagesToShow = $dp->getPager()->getPagesToShow();

```


Для модификации условий выборки WebDataProvider использует классы фильтров *Legal\Core\Data\Filter*

```php

<?php
namespace App\Filters;

use Legal\Core\Data\Filter;
use Legal\Core\Db\ReadCondition;

/**
 *
 *
 * Class Filter
 * @package Legal\Core\Data
 * @author citizenzet <exgamer@live.ru>
 */
class CasinoLocalizationFilter extends Filter
{
    /**
     * Метод для настройки фильтра
     *
     * @param array $params
     * @param ReadCondition $readCondition
     * @return mixed
     */
    protected function apply(array $params, ReadCondition $readCondition)
    {
        $readCondition->andWhere(['parent_id' => 13]);
    }
}

```



[Назад](../index.md "Необязательная подсказка")  