<?php
namespace concepture\core\base;

/**
 *
 * Trait GroupTrait
 *
 * @package Legal\Core\Db
 * @author citizenzer <exgamer@live.ru>
 */
trait GroupTrait
{
    protected $group = null;

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     */
    public function group(string $group): void
    {
        $this->group = $group;
    }
}

