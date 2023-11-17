<?php
namespace app\dto;
class UserDto
{
    private int $id;
    private string $email;
    private string $first_name;
    private string $last_name;
    private string $avatar;

    /**
     * Init the user data-transfer object
     */
    public function __construct(
        int    $id = null,
        string $email = null,
        string $first_name = null,
        string $last_name = null,
        string $avatar = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->avatar = $avatar;
    }

    /**
     * Get user id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get user email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get user first name
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * Get user last name
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * Get user avatar
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }
}