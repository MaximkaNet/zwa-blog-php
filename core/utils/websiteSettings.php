<?php

namespace app\core\utils;

/**
 * Website settings
 */
class WebsiteSettings
{
    /**
     * Website name
     */
    private string $name;

    /**
     * Page title
     */
    private string $page;

    /**
     * The website language
     */
    private string $lang;

    /**
     * Get page title
     * @param string $sep Separator between page name and website name
     * @return string
     */
    public function getTitle(string $sep = "-"): string
    {
        $title_parts = [];
        if(isset($this->page)) {
            $title_parts[] = $this->page;
        }
        $title_parts[] = $sep;
        if(isset($this->name)){
            $title_parts[] = $this->name;
        }
        return implode(" ", $title_parts);
    }

    /**
     * Set page name
     * @param string $page
     */
    public function setPage(string $page): void
    {
        $this->page = $page;
    }

    /**
     * Get page name
     * @return string
     */
    public function getPage(): string
    {
        return $this->page;
    }

    /**
     * Set website name
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get website name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set website language
     * @param string $lang
     * @return void
     */
    public function setLang(string $lang): void
    {
        $this->lang = $lang;
    }

    /**
     * Get website language
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }
}