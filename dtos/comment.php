<?php

namespace app\dto;

require_once "user.php";

class CommentDto
{
    private int $id;
    private CommentDto $replay_to;
    private UserDto $author;
    private string $content;

    /**
     * Init the comment data-transfer object
     * @param int|null $id
     * @param CommentDto|null $replay_to
     * @param UserDto|null $author
     * @param string|null $content
     */
    public function __construct(
        int $id = null,
        CommentDto $replay_to = null,
        UserDto $author = null,
        string $content = null
    )
    {
        $this->id = $id;
        $this->content = $content;
        $this->author = $author;
        $this->replay_to = $replay_to;
    }

    /**
     * Return the comment id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Return the comment content
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Return the comment author
     * @return UserDto
     */
    public function getAuthor(): UserDto
    {
        return $this->author;
    }

    /**
     * Return the comment answer
     * @return CommentDto
     */
    public function getReplayTo(): CommentDto
    {
        return $this->replay_to;
    }
}