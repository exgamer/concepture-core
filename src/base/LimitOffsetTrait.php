<?php
namespace concepture\core\base;

/**
 *
 * Trait LimitOffsetTrait
 * @package Legal\Core\Db
 * @author citizenzer <exgamer@live.ru>
 */
trait LimitOffsetTrait
{
    protected $limit = null;
    protected $offset = null;

    public function limit($limit)
    {
        $this->limit = (int) $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->offset = (int) $offset;

        return $this;
    }

    /**
     * @return null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return null
     */
    public function getOffset()
    {
        return $this->offset;
    }


}

