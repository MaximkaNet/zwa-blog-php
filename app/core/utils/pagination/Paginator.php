<?php

namespace app\core\utils\pagination;

use app\core\Router;

class Paginator
{
    /**
     * Number items to paginate
     */
    private int $number_items;

    /**
     * Number items on the page
     */
    private int $page_number_items = 10;

    /**
     * The first page number
     */
    private int $first_page_number = 0;

    /**
     * The last page number
     */
    private int $last_page_number = 0;

    /**
     * The current page
     */
    private int $current = 0;

    public function __construct(int $number_items)
    {
        $this->number_items = max($number_items, 0);
    }

    /**
     * Get page number
     * @param mixed $page
     * @param string $pattern Regular expression page pattern
     * @return void
     */
    public function resolve(mixed $page, string $pattern = "page(-?\d+)"): void
    {
        // Get page number
        if (isset($page)) {
            if(is_integer($page)){
                $this->current = $page;
            }
            else if(is_string($page)) {
                $str_page = htmlspecialchars($page);
                if (preg_match("/$pattern/", $str_page, $matches, PREG_OFFSET_CAPTURE)) {
                    $this->current = $matches[1][0];
                    $this->current -= 1;
                    if ($this->current < 0) {
                        $this->current = 0;
                    }
                }
            }
        }
        // Set last page number
        $count = $this->number_items;
        $max_items = $this->page_number_items;
        $max_page_number = floor($count / $max_items);
        if ($count % $max_items > 0) {
            $max_page_number += 1;
        }
        $this->last_page_number = $max_page_number;
    }

    /**
     * Generate paginator scheme
     * @param string $location The paginator location.
     * @return array
     */
    public function generate(string $location): array
    {
        $items = [];
        for ($i = 0; $i < $this->last_page_number; $i++) {
            $items[] = [
                "link" => Router::link($location . "/page" . $i + 1),
                "content" => (string)($i + 1),
                "current" => $i == $this->current
            ];
        }
        return $items;
    }

    /**
     * Get current page number. The countdown starts from zero
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->current;
    }

    /**
     * Set number items on the page
     */
    public function setPageNumberItems(int $count): void
    {
        $this->page_number_items = $count;
    }

    /**
     * Get number items on the page
     */
    public function getPageNumberItems(): int
    {
        return $this->page_number_items;
    }

    /**
     * Get last page number
     */
    public function getLastPageNumber(): int
    {
        return $this->last_page_number;
    }

    /**
     * Get first page number
     */
    public function getFirstPageNumber(): int
    {
        return $this->first_page_number;
    }
}