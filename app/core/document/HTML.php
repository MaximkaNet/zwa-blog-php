<?php

namespace app\core\document;

class HTML extends DocumentElement
{
    public function __construct(
        string $lang = "en"
    ) {
        parent::__construct("html");
        $this->addAttribute("lang", $lang);
    }

    /**
     * Get website language
     * @return string|null
     */
    public function getLang(): string|null
    {
        return $this->getAttribute("lang") ?? null;
    }

    /**
     * Get document head
     * @return DocumentElement|null
     */
    public function getHead(): DocumentElement|null
    {
        return $this->findChild("head");
    }

    /**
     * Set document head
     * @param DocumentElement $head
     * @return void
     */
    public function setHead(DocumentElement $head): void
    {
        $this->appendChild($head);
    }

    /**
     * Get document body
     * @return DocumentElement|null
     */
    public function getBody(): DocumentElement|null
    {
        return $this->findChild("body");
    }

    /**
     * Set document body
     * @param DocumentElement $body
     * @return void
     */
    public function setBody(DocumentElement $body): void
    {
        $this->appendChild($body);
    }
}