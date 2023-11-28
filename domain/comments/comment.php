<?php

namespace app\domain\entity;

require_once "../../core/entity.php";

use app\core\entity\Entity;

require_once "../users/user.php";

class Comment extends Entity
{
    protected ?int $id;
    protected ?User $author;
    protected ?string $content;
//    private ?Comment $parent;
    private bool $is_edited;

    public function __construct(
        int $id = null,
        User $author = null,
        string $content = null,
//        Comment $parent = null,
        bool $is_edited = false
    )
    {
        $this->id = $id;
        $this->author = $author;
        $this->content = $content;
//        $this->parent = $parent;
        $this->is_edited = $is_edited;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

//    public function getParentComment(): Comment
//    {
//        return $this->parent;
//    }

    /**
     * @param string $content
     */
    public function editContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Check comment status
     * @return bool
     */
    public function isEdited(): bool
    {
        return $this->is_edited;
    }
}