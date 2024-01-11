<?php

namespace app\core\document;

use app\core\interfaces\IDocumentElementFactory;

class DocumentElement implements IDocumentElementFactory
{
    /**
     * Element tag
     */
    protected string $tag_name;

    /**
     * The attributes
     */
    protected array $attributes = [];

    /**
     * Element inner text
     */
    protected string $inner_text = "";

    /**
     * Element children
     * @var self[]
     */
    protected array $children = [];

    /**
     * Paired flag
     */
    protected bool $paired = false;

    /**
     * Create a new document element
     * @param string $tag_name
     * @param array $children
     * @param bool $paired
     */
    public function __construct(string $tag_name, array $children = [], bool $paired = true)
    {
        $this->tag_name = $tag_name;
        $this->children = $children;
        $this->paired = $paired;
    }

    /**
     * Create a new document element
     * @param string $tag_name
     * @param bool $paired
     * @return self
     */
    public static function createElement(string $tag_name, bool $paired = true): self
    {
        return new DocumentElement($tag_name, [], $paired);
    }

    /**
     * Set inner text
     * @param string $inner_text
     * @return self
     */
    public function setInnerText(string $inner_text): self
    {
        $this->inner_text = $inner_text;
        return $this;
    }

    /**
     * Get tag name
     * @return string
     */
    public function getTagName(): string
    {
        return $this->tag_name;
    }

    /**
     * Build an HTML element. This function convert element and children
     * elements (if exists) to html tag with inner text
     * @return string
     */
    public function build(): string
    {
        $inner_text = $this->inner_text;
        $children = $this->buildChildren();
        $attributes = $this->buildAttributes();
        if($this->paired)
            return "<$this->tag_name $attributes>$inner_text$children</$this->tag_name>";
        return "<$this->tag_name $attributes>";
    }

    /**
     * Build an attributes
     * @return string
     */
    private function buildAttributes(): string
    {
        $result = "";
        foreach ($this->attributes as $attribute => $content) {
            $content_or_collection = $content;
            if(is_array($content)) {
                $content_or_collection = implode(" ", $content);
            }
            $result .= "$attribute" . (isset($content) ? "=\"$content_or_collection\"" : "") . " ";
        }
        return $result;
    }

    /**
     * Build a children
     * @return string
     */
    private function buildChildren(): string
    {
        $result = "";
        foreach ($this->children as $child) {
             $result .= $child->build();
        }
        return $result;
    }

    /**
     * Add attribute
     * @param string $key
     * @param string|array $content
     * @return self
     */
    public function addAttribute(string $key, string|array $content): self
    {
        $this->attributes[$key] = $content;
        return $this;
    }

    /**
     * Get all attributes
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get attribute
     * @param string $name
     * @return array|string|null
     */
    public function getAttribute(string $name): array|string|null
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Check if attribute exists
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        return key_exists($name, $this->attributes);
    }

//    /**
//     * Set document element id
//     * @param string $id
//     * @return self
//     */
//    public function setId(string $id): self
//    {
//        $this->attributes["id"] = $id;
//        return $this;
//    }

//    /**
//     * Get id
//     * @return string|null
//     */
//    public function getId(): ?string
//    {
//        return $this->attributes["id"] ?? null;
//    }

    /**
     * Get class list
     * @return array|null Returns null if classes is not defined
     */
    public function getClassList(): ?array
    {
        return $this->attributes["class"] ?? null;
    }

    /**
     * Add class
     * @param string $name
     * @return self
     */
    public function addClass(string $name): self
    {
        $this->attributes["class"][$name] = $name;
        return $this;
    }

    /**
     * Add classes
     * @param array $classes
     * @return self
     */
    public function addClasses(array $classes): self
    {
        foreach ($classes as $class) {
            $this->attributes["class"][$class] = $class;
        }
        return $this;
    }

    /**
     * Append child
     * @param self|self[] $children
     * @return self
     */
    public function appendChild(self|array $children): self
    {
        if (is_array($children)) {
            foreach ($children as $child) {
                $this->children[] = $child;
            }
        } else {
            $this->children[] = $children;
        }
        return $this;
    }

    /**
     * Get inner text
     * @return string
     */
    public function getInnerText(): string
    {
        return $this->inner_text ?? "";
    }

    /**
     * Get children
     * @return array|DocumentElement[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Gets the first founded child
     * @param string $tag_name
     * @return mixed
     */
    public function findChild(string $tag_name): mixed
    {
        foreach ($this->children as $child) {
            if($child->getTagName() === $tag_name)
                return $child;
        }
        return null;
    }
}