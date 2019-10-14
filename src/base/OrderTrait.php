<?php
namespace concepture\core\base;

/**
 *
 * Trait OrderTrait
 * @package Legal\Core\Db
 * @author citizenzer <exgamer@live.ru>
 */
trait OrderTrait
{
    protected $order = null;

    public function order($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return null
     */
    public function getOrder()
    {
        return $this->order;
    }


}

