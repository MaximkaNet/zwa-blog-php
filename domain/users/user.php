<?php
namespace app\domain\entity;

require_once "../../core/entity.php";

use app\core\entity\Entity;

class User extends Entity
{
    private ?int $id;
    private ?string $email;
    private ?string $password; /* Bcrypt hash (60 chars)*/
    private ?string $first_name;
    private ?string $last_name;
    private ?string $avatar;

    /**
     * Init a user
     * @param int|null $id
     * @param string|null $email
     * @param string|null $password must be bcrypt hash
     * @param string|null $first_name
     * @param string|null $last_name
     * @param string|null $avatar
     */
    public function __construct(
        int $id = null,
        string $email = null,
        string $password = null,
        string $first_name = null,
        string $last_name = null,
        string $avatar = null
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->avatar = $avatar;
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string|null $first_name
     */
    public function setFirstName(?string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @param string|null $last_name
     */
    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Return full username
     * @return string
     */
    public function getFullName(): string
    {
        $full_username = $this->first_name;
        if(isset($this->last_name))
            $full_username .= " " . $this->last_name;
        return $full_username;
    }

    /**
     * Set new password using password_hash function
     * @param string $new_password
     */
    public function changePassword(string $new_password): void
    {
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $this->password = $password_hash;
    }

    /**
     * @param string $avatar
     */
    public function changeAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }
}