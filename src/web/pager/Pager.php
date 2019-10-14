<?php
namespace concepture\core\web\pager;

use concepture\core\base\Component;

/**
 * Class Pager
 * @package Legal\Core\Web\Pager
 * @author citizenzet <exgamer@live.ru>
 */
class Pager extends Component
{
    private $totalCount = 0;
    private $page = 1;
    private $pageSize = 10;
    private $shownPagesCount = 10;

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @return int
     */
    public function getShownPagesCount(): int
    {
        return $this->shownPagesCount;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page)
    {
        $this->page = $page;
    }

    /**
     * @param int $totalCount
     */
    public function setTotalCount(int $totalCount)
    {
        $this->totalCount = $totalCount;
    }

    /**
     * @param int $pageSize
     */
    public function setPageSize(int $pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @param int $shownPagesCount
     */
    public function setShownPagesCount(int $shownPagesCount)
    {
        $this->shownPagesCount = $shownPagesCount;
    }

    /**
     * returns total pages count
     *
     * @return integer
     */
    public function getTotalPagesCount() : int
    {
        $totalPagesCount = (int) $this->totalCount/(int) $this->pageSize;
        $totalPagesCount = ceil($totalPagesCount);

        return (int) $totalPagesCount;
    }

    /**
     * Возвращает массив со страницами для показа
     *
     * [
     *      page => [
     *        'label' => 1
     *     ]
     * ]
     *
     * @return array
     */
    public function getPagesToShow()
    {
        $pages = [];
        $totalPagesCount= $this->getTotalPagesCount();
        $leftSideShownPagesCount = (int) ceil($this->shownPagesCount/2);
        $rightSideShownPagesCount = $this->shownPagesCount - $leftSideShownPagesCount;
        $startPage = $this->page - $leftSideShownPagesCount;
        if ($startPage < 1) {
            $startPage = 1;
        }
        $endPage = $this->page + $rightSideShownPagesCount;
        if ($endPage > $totalPagesCount) {
            $endPage = $totalPagesCount;
        }
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $startPage && $startPage > 1) {
                $k = $i - $this->shownPagesCount;
                if ($k < 1) {
                    $k = 1;
                }
                $pages[$k] = [
                    'label' => "..."
                ];
                continue;
            }
            if ($i == $endPage && $endPage < $totalPagesCount) {
                $k = $i + $this->shownPagesCount;
                if ($k > $totalPagesCount) {
                    $k = $totalPagesCount;
                }
                $pages[$k] = [
                    'label' => "..."
                ];
                continue;
            }
            $pages[$i] = [
                'label' => $i
            ];
        }

        return $pages;
    }
}