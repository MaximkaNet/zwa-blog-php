<?php

namespace app\dto;

require_once "user.php";

class PostDto
{
    private int $id;
    private string $title;
    private string $content;
    private string $date;
    private UserDto $creator;
    private int $rating;
//    private int $count_saved;

    /**
     * Init the post data-transfer object
     * @param int|null $id
     * @param string|null $title
     * @param string|null $content
     * @param string|null $date
     * @param UserDto|null $creator
     * @param int|null $rating
     */
    public function __construct(
        int $id = null,
        string $title = null,
        string $content = null,
        string $date = null,
        UserDto $creator = null,
        int $rating = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->date = $date;
        $this->creator = $creator;
        $this->rating= $rating;
    }

    /**
     * Return the post id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Return the post title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Return the post content
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Return the post creator data-transfer object
     * @return UserDto
     */
    public function getCreator(): UserDto
    {
        return $this->creator;
    }

    /**
     * Return the post date
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Return the post rating
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }
}