<?php

namespace app\core\interfaces;

use app\core\exception\RepositoryException;
use PDO;

interface IRepositoryFactory
{
    /**
     * Initialize repository
     * @param PDO $pdo
     * @return mixed
     */
    public static function init(PDO $pdo): mixed;

    /**
     * The function checks whether the repository is initialized
     * @return bool
     */
    public static function in_init(): bool;

    /**
     * Get repository
     * @return mixed
     * @throws RepositoryException Throws if repository is uninitialized
     */
    public static function getRepository(): mixed;

    /**
     * Get entity scheme
     * @param array|null $exclude
     * @return array
     */
    public static function getScheme(array $exclude = null): array;

    /**
     * Get table name
     * @return string
     */
    public static function getTableName(): string;

    /**
     * The number of records
     * @param mixed|null $filter
     * @return int
     */
    function count(mixed $filter = null): int;

    /**
     * Find record by id and returns new entity instance
     * @param mixed $id
     * @return mixed
     */
    function findById(int $id): mixed;

    /**
     * Find one record and returns new entity instance
     * @param array $where
     * @return mixed
     */
    function findOne(array $where): mixed;

    /**
     * Find all records and returns new entity instances
     * @param array|null $where
     * @param array|null $options
     * @return array|null
     */
    function findAll(array $where = null, array $options = null): ?array;

    /**
     * Create a new record
     * @param array $values
     * @return int
     * @throws RepositoryException
     */
    function create(array $values): int;

    /**
     * Update records
     * @param array $values
     * @param array|null $where
     * @return void
     */
    function update(array $values, array $where = null): void;

    /**
     * Delete record
     * @param array|null $where
     * @return void
     */
    function delete(array $where = null): void;
}