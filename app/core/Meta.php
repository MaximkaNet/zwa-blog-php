<?php

namespace app\core;

class Meta
{
    public const LANG_EN = "en";
    public const LANG_CZ = "cz";
    public const LANG_UA = "uk";
    public const LANG_RU = "ru";

    private string $styles = "";
    private string $favicon = "";
    private string $lang = "en";

    public function getLanguage(): string
    {
        return $this->lang;
    }

    public function setLanguage(string $lang): void
    {
        $this->lang = $lang;
    }

    public function getFaviconLink(): string
    {
        return $this->favicon;
    }

    public function setFaviconLink(string $link): void
    {
        $this->favicon = $link;
    }
}