<?php

namespace domain\posts;

use domain\categories\Category;
use domain\users\User;

interface IPost
{
    /**
     * Create a new post object
     */
    public function __construct();

    /**
     * Get id
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get title
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Set title
     * @param string $title
     */
    public function setTitle(string $title): void;

    /**
     * Get content
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Set content
     * @param string $content
     */
    public function setContent(string $content): void;

    /**
     * Get count saved
     * @return int|null
     */
    public function getCountSaved(): ?int;

    /**
     * Set count saved
     * @param int $saved
     * @return void
     */
    public function setCountSaved(int $saved): void;

    /**
     * Add save score
     * @return void
     */
    public function addSave(): void;

    /**
     * Subtract save score
     * @return void
     */
    public function subtractSave(): void;

    /**
     * Get users
     * @return User|null
     */
    public function getUser(): ?User;

    /**
     * Get category
     * @return Category|null
     */
    public function getCategory(): ?Category;

    /**
     * Set category
     * @param Category $category
     */
    public function setCategory(Category $category): void;

    /**
     * Get rating
     * @return int|null
     */
    public function getRating(): ?int;

    /**
     * Set rating
     * @param int $rating
     */
    public function setRating(int $rating): void;

    /**
     * Get status
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set status
     */
    public function setStatus(string $status): void;
    public function getCreationDateTime(): ?string;
}