<?php

namespace domain\users;

class User implements IUser
{
    /**
     * The users identification
     */
    private int $id;

    /**
     * The users email
     */
    private string $email;

    /**
     * The password <br>
     * Bcrypt hash (60 chars)
     */
    private string $password;

    /**
     * The full name
     */
    private string $full_name;

    /**
     * The username (15 chars)
     */
    private string $username;

    /**
     * The users avatar
     */
    private string $avatar;

    /**
     * The users role
     */
    private string $role;

    /**
     * The users added date
     */
    private string $created_at;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getUserName(): ?string
    {
        return $this->username ?? null;
    }

    public function getFullName(): ?string
    {
        return $this->full_name ?? null;
    }

    public function setFullName(string $full_name): void
    {
        $this->full_name = $full_name;
    }

    public function getEmail(): ?string
    {
        return $this->email ?? null;
    }

    public function getPassword(): ?string
    {
        return $this->password ?? null;
    }

    public function setPassword(string $password, bool $make_hash = false): void
    {
        if ($make_hash) {
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        } else {
            if (strlen($password) > 60) {
                throw new UserException("Hash must be less or equal then 60 characters");
            }
            $this->password = $password;
        }
    }

    public function getAvatar(): ?string
    {
        return $this->avatar ?? null;
    }

    public function setAvatar(string $filename): void
    {
        $this->avatar = $filename;
    }

    public function getRole(): ?string
    {
        return $this->role ?? null;
    }

    public function setRole(string $role): void
    {
        if (
            $role === UserRole::USER ||
            $role === UserRole::ADMIN ||
            $role === UserRole::MODERATOR
        ) {
            $this->role = $role;
        } else {
            throw new UserException("Incorrect role given");
        }
    }

    public static function getRoleLevel(string $role): int
    {
        return match ($role) {
            UserRole::USER => 0,
            UserRole::MODERATOR => 1,
            UserRole::ADMIN => 2,
            default => throw new UserException("Incorrect role")
        };
    }

    public function getDatetimeOfCreate(): ?string
    {
        return $this->created_at ?? null;
    }

    public function canPromoteTo(string $role): bool
    {
        $this_role = $this->getRole();
        if (empty($this_role)) {
            throw new UserException("Role is not set");
        }
        $this_level = self::getRoleLevel($this_role);
        $subject_level = self::getRoleLevel($role);
        return $this_level >= $subject_level;
    }
}