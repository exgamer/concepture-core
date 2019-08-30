<?php
namespace concepture\core\helper;

use concepture\core\application\base\Application;

/**
 * Вспомогательный класс
 *
 * @author CitizenZet <exgamer@live.ru>
 */
class ApplicationHelper
{
    /**
     * $config = [
     *    'domains' => [
     *       ....
     *    ]
     *
     * ];
     *
     * ApplicationHelper::init($config);
     *
     * @param array $config
     * @return Application
     */
    public static function init($config = [])
    {
        $app = new Application($config);

        return $app;
    }

}