<?php
namespace concepture\core\base;

/**
 * Класс для управления запросом
 *
 * ReadQuery
 *
 * @author citizenzer <exgamer@live.ru>
 */
class ReadCondition extends BaseObject
{
    protected $params = [];

    use WhereTrait;
    use JoinTrait;
    use SelectionTrait;
    use OrderTrait;
    use LimitOffsetTrait;
    use GroupTrait;

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}