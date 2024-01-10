<?php
namespace domain\posts;

use domain\users\User;
use domain\categories\Category;

/**
 * The Post entity
 */
class Post implements IPost
{
    /**
     * The post identification
     */
    private int $id;

    /**
     * The post title
     */
    private string $title;

    /**
     * The post content
     */
    private string $content;

    /**
     * The post rating
     */
    private int $rating;

    /**
     * The post count saved
     */
    private int $count_saved;

    /**
     * The post author
     */
    private User $user;

    /**
     * The post category
     */
    private Category $category;

    /**
     * The post status (draft, posted or trash)
     */
    private string $status;

    /**
     * The post creation date
     */
    private string $created_at;

    public function __construct()
    {
        $this->status = PostStatus::DRAFT;
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getTitle(): ?string
    {
        return $this->title ?? null;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content ?? null;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCountSaved(): ?int
    {
        return $this->count_saved ?? null;
    }

    public function setCountSaved(int $saved): void
    {
        $this->count_saved = $saved;
    }

    public function addSave(): void
    {
        $this->count_saved += 1;
    }

    public function subtractSave(): void
    {
        $this->count_saved -= 1;
    }

    public function getUser(): ?User
    {
        return $this->user ?? null;
    }

    public function getCategory(): ?Category
    {
        return $this->category ?? null;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function getRating(): ?int
    {
        return $this->rating ?? null;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCreationDateTime(): ?string
    {
        return $this->created_at ?? null;
    }
}