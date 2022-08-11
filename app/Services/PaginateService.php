<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\PaginationException;

class PaginateService
{

    /**
     * Distance between current page to the left and right border of page.
     *
     * @var int
     */
    protected int $pageDepth = 2;

    /**
     * @var int
     */
    protected int $pageSideDepth;

    public function __construct()
    {
        $this->pageSideDepth = (3 * $this->pageDepth) - 1;
    }

    /**
     * Paginate items
     *
     * @param array $items
     * @param int $page current page
     * @param int $size size of pagination
     * @param int $totalCount total count of items in pagination
     * @return array
     * @throws PaginationException
     */
    public function getPagination(array $items, int $page, int $size, int $totalCount): array
    {
        $totalPages = intval($totalCount / $size);

        if ($page > $totalPages || $page < 1)
            throw new PaginationException('Undefined page');

        $data = $items;
        $pagination = $this->mapLinks($this->getPaginationArray($page, $totalPages));

        return [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'pages' => $pagination,
            'data' => $data,
        ];
    }

    /**
     * Get range between start(left) and end(right)
     *
     * @param int $start
     * @param int $end
     * @return array     range from left to right border
     */
    private function getRange(int $start, int $end): array
    {
        $range = [];
        $length = $end - $start;

        for ($i = 0; $i <= $length; $i++)
            $range[$i] = $i + $start;

        return $range;
    }

    /**
     * @param int $page
     * @param int $totalPages
     * @return array
     */
    private function getPaginationArray(int $page, int $totalPages): array
    {
        /**
         * When pages less than sideDepth, show full pages
         */
        if ($this->pageSideDepth >= $totalPages)
            return $this->getRange(1, $totalPages);

        $leftBorder = max($page - $this->pageDepth, 1);
        $rightBorder = min($page + $this->pageDepth, $totalPages);

        /**
         * Checking if current page touch border in left or right sides.
         */
        $isLeftBorder = $leftBorder < $this->pageDepth;
        $isRightBorder = $rightBorder > $totalPages - $this->pageDepth;

        if ($isLeftBorder && !$isRightBorder) {
            $left = $this->getRange(1, $this->pageSideDepth);

            return [...$left, '...', $totalPages];
        }

        if (!$isLeftBorder && $isRightBorder) {
            $right = $this->getRange($totalPages - $this->pageSideDepth, $totalPages);

            return [1, '...', ...$right];
        }

        /**
         * If not we are on center of pagination.
         */
        $center = $this->getRange($leftBorder, $rightBorder);

        return [1, '...', ...$center, '...', $totalPages];
    }

    private function mapLinks(array $pagination): array
    {
        $map = function ($page) {
            return ['page' => $page, 'link' => "link: $page"];
        };

        return array_map($map, $pagination);
    }
}
