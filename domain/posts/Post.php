<?php
namespace app\domain\entity;

require_once "../../core/entity.php";
use app\core\entity\Entity;

require_once "../users/user.php";
require_once "../categories/category.php";

class Post extends Entity
{
    protected ?int $id;
    protected ?string $title;
    protected ?string $content;
    protected ?int $rating;
    protected ?int $count_saved;
    protected ?User $user;
    protected ?Category $category;
    protected bool $is_edited;

    /**
     * Init a post
     * @param int|null $id
     * @param string|null $title
     * @param string|null $content
     * @param int|null $rating
     * @param int|null $count_saved
     * @param User|null $author
     * @param Category|null $category
     * @param bool $is_edited
     */
    public function __construct(
        int $id = null,
        string $title = null,
        string $content = null,
        int $rating = null,
        int $count_saved = null,
        User $author = null,
        Category $category = null,
        bool $is_edited = false
    )
    {
        $this->id = $id;
        $this->user = $author;
        $this->category = $category;
        $this->title = $title;
        $this->content = $content;
        $this->rating = $rating;
        $this->count_saved = $count_saved;
        $this->is_edited = $is_edited;
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return ?int
     */
    public function getCountSaved(): ?int
    {
        return $this->count_saved;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @return ?int
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param string $new_title
     * @return void
     */
    public function editTitle(string $new_title):void
    {
        $this->title = $new_title;
        $this->is_edited = true;
    }

    /**
     * @param string $new_content
     * @return void
     */
    public function editContent(string $new_content): void
    {
        $this->content = $new_content;
        $this->is_edited = true;
    }

    /**
     * @param Category $new_category
     * @return void
     */
    public function changeCategory(Category $new_category): void
    {
        $this->category = $new_category;
        $this->is_edited = true;
    }

    /**
     * Check post status
     * @return bool
     */
    public function isEdited(): bool
    {
        return $this->is_edited;
    }
}