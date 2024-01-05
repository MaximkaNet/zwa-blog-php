<?php

namespace app\core\interfaces;

use app\core\document\DocumentElement;

interface IDocumentElementFactory
{
    /**
     * Create a new document element
     * @param string $tag_name
     * @param bool $paired
     * @return mixed
     */
    public static function createElement(string $tag_name, bool $paired): mixed;

    /**
     * Set inner text
     * @param string $inner_text
     * @return mixed
     */
    public function setInnerText(string $inner_text): mixed;

    /**
     * Build an HTML element
     * @return string
     */
    public function build(): string;

    /**
     * Append child
     * @param DocumentElement|array $children
     * @return mixed
     */
    public function appendChild(DocumentElement|array $children): mixed;

//    /**
//     * Set element id
//     * @param string $id
//     * @return mixed
//     */
//    public function setId(string $id): mixed;
//
//    /**
//     * Get element id
//     * @return ?string
//     */
//    public function getId(): ?string;

    /**
     * Get class list
     * @return array|null Returns null if classes is not defined
     */
    public function getClassList(): ?array;

    /**
     * Add class
     * @param string $name
     * @return mixed
     */
    public function addClass(string $name): mixed;

    /**
     * Add classes
     * @param array $classes
     * @return mixed
     */
    public function addClasses(array $classes): mixed;

    /**
     * Get inner text
     * @return string
     */
    public function getInnerText(): string;

    /**
     * Get children
     * @return array
     */
    public function getChildren(): array;

    /**
     * Add attribute
     * @param string $key
     * @param string|array $content
     * @return mixed
     */
    public function addAttribute(string $key, string|array $content): mixed;

    /**
     * Get all attributes
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Get attribute
     * @param string $name
     * @return array|string|null
     */
    public function getAttribute(string $name): string|array|null;

    /**
     * Check if attribute exists
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool;

    /**
     * Gets the first founded child
     * @param string $tag_name
     * @return mixed
     */
    public function findChild(string $tag_name): mixed;
}