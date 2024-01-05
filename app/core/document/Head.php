<?php

namespace app\core\document;

class Head extends DocumentElement
{
    /**
     * Charset meta tag
     */
    private string $charset;

    /**
     * Viewport meta tag
     */
    private string $viewport_content;

    /**
     * Website title
     */
    private string $title;

    /**
     * Link to favicon
     */
    private string $favicon_link;

    /**
     * Scripts
     */
    private array $scripts;

    /**
     * Stylesheets
     */
    private array $stylesheets;

    /**
     * Noscript content
     */
    private string $noscript_content;

    /**
     * Initialize head
     */
    public function __construct(
        string $charset = "UTF-8",
        string $viewport_content = "width=device-width, initial-scale=1.0"
    ) {
        parent::__construct("head");
        $this->charset = $charset;
        $this->viewport_content = $viewport_content;
        $this->stylesheets = [];
        $this->scripts = [];
    }

    /**
     * Get website title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?? "";
    }

    /**
     * Set website title
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get favicon link
     */
    public function getFaviconLink(): string
    {
        return $this->favicon_link;
    }

    /**
     * Set favicon link
     * @param string $link
     * @return void
     */
    public function setFaviconLink(string $link): void
    {
        $this->favicon_link = $link;
    }

    /**
     * Get included scripts
     * @return array
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    /**
     * Get included stylesheets
     * @return array
     */
    public function getStylesheets(): array
    {
        return $this->stylesheets;
    }

    /**
     * Add script
     * @param string $src If the source existed in scripts. Script will be overwritten
     * @param string $content
     * @param array $attributes
     * @return void
     */
    public function addScript(string $src, string $content, array $attributes = []): void
    {
        $this->scripts[$src] = [
            "src" => $src,
            "content" => $content,
            "attributes" => $attributes
        ];
    }

    /**
     * Add style sheet
     * @param string $href If the href existed in style sheets. Style sheet will be overwritten
     * @return void
     */
    public function addStyleSheet(string $href): void
    {
        $this->stylesheets[$href] = [
            "href" => $href
        ];
    }

    /**
     * Set no script tag
     * @param string $content
     * @return void
     */
    public function setNoScriptContent(
        string $content = "Available JavaScript to use this website"
    ): void {
        $this->noscript_content = $content;
    }

    /**
     * Get no script content
     * @return string|null
     */
    public function getNoScriptContent(): ?string
    {
        return $this->noscript_content ?? null;
    }

    /**
     * Build an entire \<head\> tag
     * @return string
     */
    public function build(): string
    {
        $meta_charset = DocumentElement::createElement("meta", false)
            ->addAttribute("charset", $this->charset);
        $meta_viewport = DocumentElement::createElement("meta", false)
            ->addAttribute("name", "viewport")
            ->addAttribute("content", $this->viewport_content);

        $this->appendChild([$meta_charset, $meta_viewport]);

        if (isset($this->favicon_link)) {
            $link_favicon = DocumentElement::createElement("link", false)
                ->addAttribute("rel", "icon")
                ->addAttribute("type", "image/x-icon")
                ->addAttribute("href", $this->favicon_link);
            $this->appendChild($link_favicon);
        }
        $title = DocumentElement::createElement("title")
            ->setInnerText($this->title);
        $this->appendChild($title);
        // Add scripts
        foreach ($this->scripts as $script => ["src" => $href, "attributes" => $attributes, "content" => $content]) {
            $script = DocumentElement::createElement("script")
                ->addAttribute("src", $href)
                ->setInnerText($content);
            foreach ($attributes as $attribute => $value) {
                $script->addAttribute($attribute, $value);
            }
            $this->appendChild($script);
        }
        // Add links
        foreach ($this->stylesheets as $stylesheet => ["href" => $href]) {
            $link = DocumentElement::createElement("link", false)
                ->addAttribute("rel", "stylesheet")
                ->addAttribute("href", $href);
            $this->appendChild($link);
        }
        if (isset($this->noscript_content)) {
            $noscript = DocumentElement::createElement("noscript")
                ->setInnerText($this->noscript_content);
            $this->appendChild($noscript);
        }
        return parent::build();
    }
}