<?php

namespace domain\categories;

use app\core\database\EntityManager;
use app\core\exception\RepositoryException;
use app\core\interfaces\IRepositoryFactory;
use app\core\utils\queryBuilder\QueryBuilder;
use PDO;

class CategoriesRepository implements IRepositoryFactory
{
    /**
     * Table name in database
     */
    private static string $table_name = "categories";

    /**
     * Data structure mapping for interactions with raw
     * data (from the database) and entity in the application
     */
    private static array $scheme = [
        /* <column in database> => <property in entity> */
        "id" => "id",
        "name" => "name",
        "display_name" => "display_name",
        "position" => "pos"
    ];

    /**
     * Database abstraction layer for interaction with database
     */
    private PDO $pdo;

    private static self $instance;

    private function __clone(): void
    {
    }

    private function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function init(PDO $pdo): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self($pdo);
        }
        return self::$instance;
    }

    public static function in_init(): bool
    {
        return isset(self::$instance);
    }

    public static function getRepository(): self
    {
        if (empty(self::$instance)) {
            throw new RepositoryException("Repository uninitialized");
        }
        return self::$instance;
    }

    public static function getScheme(array $exclude = null): array
    {
        if (isset($exclude)) {
            $with_exclude = [...self::$scheme];
            foreach ($exclude as $key) {
                unset($with_exclude[$key]);
            }
            return $with_exclude;
        }
        return self::$scheme;
    }

    public static function getTableName(): string
    {
        return self::$table_name;
    }

    function count(mixed $filter = null): int
    {
        $table_name = self::$table_name;
        $query = "SELECT COUNT(*) as `count` FROM `$table_name`";
        $stmt = $this->pdo->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result["count"];
    }

    /**
     * @return Category|null
     */
    function findById(int $id): ?Category
    {
        $result = $this->findAll(["id" => $id], ["limit" => ["limit" => 1]]);
        if (isset($result)) {
            return $result[0];
        }
        return null;
    }

    /**
     * @return Category|null
     */
    function findOne(array $where = null): ?Category
    {
        $result = $this->findAll($where, ["limit" => ["limit" => 1]]);
        if (isset($result)) {
            return $result[0];
        }
        return null;
    }

    /**
     * @return array<Category>|null
     */
    function findAll(array $where = null, array $options = null): ?array
    {
        $scheme = self::$scheme;
        $table_name = self::$table_name;
        $qb = new QueryBuilder();
        $qb->select(array_keys($scheme))
            ->from($table_name)
            ->sortAsc("position");
        $stmt = $this->pdo->query($qb->getSQL());
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if ($result) {
            $categories = [];
            foreach ($result as $row) {
                $category = new Category();
                EntityManager::applyValuesToEntity($category, $row, $scheme);
                $categories[] = $category;
            }
            return $categories;
        }
        return null;
    }

    function create(array $values): int
    {
        $table_name = self::$table_name;
        $qb = new QueryBuilder();
        $qb->insertInto($table_name, $values);
        $query = $qb->getSQL();
        $stmt = $this->pdo->prepare($query);
        if (!$stmt->execute($qb->getParamsWithValues())) {
            $error = $stmt->errorInfo();
            $driver_error_message = $error[2];
            $driver_error_code = $error[1];
            $stmt->closeCursor();
            throw new RepositoryException($driver_error_message, $driver_error_code);
        }
        $last_inserted_id = $this->pdo->lastInsertId();
        $stmt->closeCursor();
        return $last_inserted_id;
    }

    function update(array $values, array $where = null): void
    {
        $table_name = self::$table_name;
        $qb = new QueryBuilder();
        $qb->update($table_name, $values)
            ->where($where);
        $query = $qb->getSQL();
        $stmt = $this->pdo->prepare($query);
        if (!$stmt->execute($qb->getParamsWithValues())) {
            // Debug TODO: REMOVE
            $error = $stmt->errorInfo();
            $driver_error_message = $error[2];
            $driver_error_code = $error[1];
            $stmt->closeCursor();
            throw new RepositoryException($driver_error_message, $driver_error_code);
        }
        $stmt->closeCursor();
    }

    function delete(array $where = null): void
    {
        $table_name = self::$table_name;
        $qb = new QueryBuilder();
        $qb->deleteFrom($table_name)
            ->where($where);
        $query = $qb->getSQL();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($qb->getParamsWithValues());
        $stmt->closeCursor();
    }
}