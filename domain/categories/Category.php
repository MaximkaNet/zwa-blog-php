<?php

namespace domain\categories;

class Category implements ICategory
{
    private int $id;
    private string $name;
    private string $display_name;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function getDisplayName(): ?string
    {
        return $this->display_name ?? null;
    }

    public function setDisplayName(string $new_display_name): void
    {
        if (strlen($new_display_name) > 15) {
            throw new CategoryException("Exceeds the number of characters in display name");
        }
        $this->display_name = $new_display_name;
    }

    public static function isValidName(string $name): bool
    {
        $available_chars_pattern = "/[^0-9a-zA-Z-_]/";
        // Category name validation
        // Available chars are '_', '-' and all letters and numbers
        return preg_match($available_chars_pattern, $name);
    }
}