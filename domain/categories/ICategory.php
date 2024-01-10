<?php

namespace domain\categories;

interface ICategory
{
    /**
     * Create a new category object
     */
    public function __construct();

    /**
     * Get category position
     * @return int
     */
    public function getPosition(): int;

    /**
     * Get category id
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get category name
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Get category display name
     * @return string|null
     */
    public function getDisplayName(): ?string;

    /**
     * Set category display name
     * @param string $new_display_name
     * @throws CategoryException Throws CategoryException if number of characters more then 15
     */
    public function setDisplayName(string $new_display_name): void;

    /**
     * Validate a category name
     * @return bool If name is valid return <b>true</b>, otherwise <b>false</b>
     */
    public static function isValidName(string $name): bool;
}