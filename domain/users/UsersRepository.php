<?php

namespace domain\users;

use app\core\database\EntityManager;
use app\core\exception\RepositoryException;
use app\core\interfaces\IRepositoryFactory;

use app\core\utils\queryBuilder\QueryBuilder;
use PDO;
use PDOException;


class UsersRepository implements IRepositoryFactory
{
    /**
     * Table name in database
     */
    private static string $table_name = "users";

    /**
     * Data structure mapping for interactions with raw
     * data (from the database) and entity in the application
     */
    private static array $scheme = [
        /* <column in database> => <property in entity> */
        "id" => "id",
        "email" => "email",
        "username" => "username",
        "password" => "password",
        "full_name" => "full_name",
        "avatar" => "avatar",
        "role" => "role",
        "created_at" => "created_at",
    ];

    /**
     * Database abstraction layer for interaction with database
     */
    private PDO $pdo;

    /**
     * The repository instance
     */
    private static self $instance;

    private function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function init(PDO $pdo): self
    {
        if(empty(self::$instance)){
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
        if(empty(self::$instance))
            throw new RepositoryException("Repository uninitialized");
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
        } else {
            return self::$scheme;
        }
    }

    public static function getTableName(): string
    {
        return self::$table_name;
    }

    function count(mixed $filter = null): int
    {
        $table_name = $this->table_name;
        $query = "SELECT COUNT(*) as `count` FROM `$table_name`";
        $stmt = $this->pdo->query($query, PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result["count"];
    }

    /**
     * @return User|null
     */
    function findById(int $id): ?User
    {
        $results = $this->findAll(["id" => $id], ["limit" => ["limit" => 1]]);
        if(isset($results))
            return $results[0];
        return null;
    }

    /**
     * @return User|null
     */
    function findOne(array $where = null): ?User
    {
        $results = $this->findAll($where, ["limit" => ["limit" => 1]]);
        if(isset($results))
            return $results[0];
        return null;
    }

    /**
     * @return array<User>|null
     */
    function findAll(array $where = null, array $options = null): ?array
    {
        $table_name = self::$table_name;
        $scheme = self::$scheme;
        $qb = new QueryBuilder();
        $qb->select()->from($table_name);
        $qb->where($where);
        if (isset($options["limit"]["limit"])) {
            $qb->setMaxResults($options["limit"]["limit"]);
        }
        if (isset($options["limit"]["offset"])) {
            $qb->setFirstResults($options["limit"]["offset"]);
        }

        $stmt = $this->pdo->prepare($qb->getSQL());
        $values_to_bind = $qb->getParamsWithValuesWithTypes();
        if (isset($values_to_bind)) {
            foreach ($values_to_bind as $param => ["type" => $type, "value" => $value]) {
                $stmt->bindValue($param, $value, $type ?? PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if ($result) {
            $users = [];
            foreach ($result as $row) {
                $user = new User();
                EntityManager::applyValuesToEntity($user, $row, $scheme);
                $users[] = $user;
            }
            return $users;
        }
        return null;
    }

    function create(array $values): int
    {
        $table_name = self::$table_name;
        $qb = new QueryBuilder();
        $qb->insertInto($table_name, $values);
        $stmt = $this->pdo->prepare($qb->getSQL());
        $values_to_bind = $qb->getParamsWithValuesWithTypes();
        if (isset($values_to_bind)) {
            foreach ($values_to_bind as $param => ["type" => $type, "value" => $value]) {
                $stmt->bindValue($param, $value, $type ?? PDO::PARAM_STR);
            }
        }
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $error_code = $e->errorInfo[1];
            $dup_entry_error = 1062;
            // Conflict
            if ($error_code === $dup_entry_error) {
                throw new RepositoryException("User already exists");
            }
        }
        $last_inserted_id = $this->pdo->lastInsertId();
        $stmt->closeCursor();
        return $last_inserted_id;
    }

    function update(array $values, array $where = null): void
    {
        $table_name = self::$table_name;
        $qb = new QueryBuilder();
        $qb->update($table_name, $values);
        $qb->where($where);
        $query = $qb->getSQL();
        $stmt = $this->pdo->prepare($query);
        $values_to_bind = $qb->getParamsWithValuesWithTypes();
        if (isset($values_to_bind)) {
            foreach ($values_to_bind as $param => ["type" => $type, "value" => $value]) {
                $stmt->bindValue($param, $value, $type);
            }
        }
        $res = $stmt->execute();
        var_dump($res, $query);
        $stmt->closeCursor();
    }

    function delete(array $where = null): void
    {
        $table_name = self::$table_name;
        $qb = new QueryBuilder();
        $qb->deleteFrom($table_name);
        $qb->where($where);
        $stmt = $this->pdo->prepare($qb->getSQL());
        $values_to_bind = $qb->getParamsWithValuesWithTypes();
        if (isset($values_to_bind)) {
            foreach ($values_to_bind as $param => ["type" => $type, "value" => $value]) {
                $stmt->bindValue($param, $value, $type ?? PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        $stmt->closeCursor();
    }
}