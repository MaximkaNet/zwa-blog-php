<?php

namespace domain\users;

interface IUser
{
    /**
     * Create a new users object
     */
    public function __construct();

    /**
     * Get user id
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get username
     * @return string|null
     */
    public function getUserName(): ?string;

    /**
     * Get user full name
     * @return string|null
     */
    public function getFullName(): ?string;

    /**
     * Set user full name
     * @param string $full_name
     * @return void
     */
    public function setFullName(string $full_name): void;

    /**
     * Get user email
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Get user password. Function returns the bcrypt hash
     * @return string|null
     */
    public function getPassword(): ?string;

    /**
     * Set password
     * @param string $password bcrypt hash or password in plain
     * text format (<b>$make_hash</b> will be set <b>true</b>)
     * @param bool $make_hash
     * @return void
     * @throws UserException Throws UserException if password hash more 60 characters
     */
    public function setPassword(string $password, bool $make_hash = false): void;

    /**
     * Get user avatar file name
     * @return string|null
     */
    public function getAvatar(): ?string;

    /**
     * Set user avatar file name
     * @param string $filename
     * @return void
     */
    public function setAvatar(string $filename): void;

    /**
     * Get the users role
     * @return string|null
     */
    public function getRole(): ?string;

    /**
     * Set the users role
     * @param string $role
     * @return void
     * @throws UserException Throws UserException if role is not correct
     */
    public function setRole(string $role): void;

    /**
     * Get role level
     * @param string $role
     * @return int
     * @throws UserException Throws UserException if role is not found
     */
    public static function getRoleLevel(string $role): int;

    /**
     * Get the date and time of user profile creation
     * @return string|null
     */
    public function getDatetimeOfCreate(): ?string;

    /**
     * Checks if this entity can promote another entity to $role
     * @param string $role The role to which the check will be carried out
     * @return bool
     * @throws UserException Throws UserException if role is not correct
     */
    public function canPromoteTo(string $role): bool;
}