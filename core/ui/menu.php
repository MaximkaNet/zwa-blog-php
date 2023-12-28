<?php

namespace app\core\components;

class MenuItem
{
    private string $url;
    private string $display_name;
    private bool $is_current;

    public function __construct(
        string $url,
        string $display_name,
        bool $is_current = false
    )
    {
        $this->url = $url;
        $this->display_name = $display_name;
        $this->is_current = $is_current;
    }

    /**
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->is_current;
    }

    /**
     * Set item as current
     * @return void
     */
    public function current(): void
    {
        $this->is_current = true;
    }

    /**
     * Set item as no current
     * @return void
     */
    public function noCurrent(): void
    {
        $this->is_current = false;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->display_name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    public function __get(string $name)
    {
        if(array_key_exists($name, get_object_vars($this)))
            return $this->$name;
        else
            throw new \Exception("Var '$name' is not defined");
    }
}

class Menu
{
    /**
     * Return menu items
     * @type MenuItem[]
     */
    private array $items;

    /**
     * Return menu items
     * @return MenuItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(MenuItem $item): void
    {
        $this->items[$item->getUrl()] = $item;
    }

    public function setCurrent(string $url): bool
    {
        if(isset($this->items[$url])){
            foreach ($this->items as $menu_item) {
                if($menu_item->getUrl() === $url)
                    $menu_item->current();
                else
                    $menu_item->noCurrent();
            }
            return true;
        }
        return false;
    }

    public function getCurrent(): ?MenuItem
    {
        foreach ($this->items as $menu_item) {
            if ($menu_item->isCurrent()) return $menu_item;
        }
        return null;
    }
}