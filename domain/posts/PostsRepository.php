<?php

namespace domain\posts;

use app\core\database\EntityManager;
use app\core\exception\RepositoryException;
use app\core\interfaces\IRepositoryFactory;
use app\core\utils\queryBuilder\ColumnsProvider;
use app\core\utils\queryBuilder\QueryBuilder;
use domain\categories\CategoriesRepository;
use domain\categories\Category;
use domain\users\User;
use domain\users\UsersRepository;
use PDO;

class PostsRepository implements IRepositoryFactory
{
    /**
     * Table name in database
     */
    private static string $table_name = "posts";

    /**
     * Data structure mapping for interactions with raw
     * data (from the database) and entity in the application
     */
    private static array $scheme = [
        /* <column in database> => <property in entity> */
        "id" => "id",
        "title" => "title",
        "content" => "content",
        "rating" => "rating",
        "count_saved" => "count_saved",
        "status" => "status",
        "created_at" => "created_at"
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
        if (isset($filter["category_id"])) {
            $query .= " WHERE `category_id` = :category_id";
        } else {
            if (isset($filter["user_id"])) {
                $query .= " WHERE `user_id` = :user_id";
            }
        }
        $stmt = $this->pdo->prepare($query);
        if (isset($filter["category_id"])) {
            $stmt->bindValue("category_id", $filter["category_id"], PDO::PARAM_INT);
        }
        if (isset($filter["user_id"])) {
            $stmt->bindValue("user_id", $filter["user_id"], PDO::PARAM_INT);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result["count"];
    }

    /**
     * @return Post|null
     */
    function findById(mixed $id): ?Post
    {
        $result = $this->findAll(["posts" => ["id" => $id]], ["limit" => ["limit" => 1]]);
        if (isset($result)) {
            return $result[0];
        }
        return null;
    }

    /**
     * @return Post|null
     */
    function findOne(array $where = null): ?Post
    {
        $result = $this->findAll($where, ["limit" => ["limit" => 1]]);
        if (isset($result)) {
            return $result[0];
        }
        return null;
    }

    /**
     * @return array<Post>|null
     */
    function findAll(array $where = null, array $options = null): ?array
    {
        $posts_table = self::$table_name;
        $users_table = UsersRepository::getTableName();
        $categories_table = CategoriesRepository::getTableName();
        $post_scheme = self::$scheme;

        $qb = new QueryBuilder();

        // With column provider
        $columns_provider = QueryBuilder::createColumnsProvider();
        $columns_provider->addTable($posts_table)
            ->setEntityName("post")
            ->setColumns(array_keys(self::$scheme));
        $columns_provider->addTable($users_table)
            ->setEntityName("user")
            ->setColumns(array_keys(UsersRepository::getScheme(["password"])));
        $columns_provider->addTable($categories_table)
            ->setEntityName("category")
            ->setColumns(array_keys(CategoriesRepository::getScheme()));

        $qb->select($columns_provider->generateColumns())->from($posts_table);
        $qb->innerJoin($posts_table, "user_id", $users_table, "id");
        $qb->innerJoin($posts_table, "category_id", $categories_table, "id");
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
                $stmt->bindValue($param, $value, $type);
            }
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if (!$result) {
            return null;
        }
        $posts = [];
        foreach ($result as $row) {
            // Parse row
            $parsed_result = ColumnsProvider::parse($row);
            // Create instances
            $user = new User();
            $category = new Category();
            $post = new Post();
            // Get schemes
            $user_scheme = UsersRepository::getScheme();
            $category_scheme = CategoriesRepository::getScheme();
            $post_scheme = [...$post_scheme, "user" => "user", "category" => "category"];
            // Apply values to entity
            EntityManager::applyValuesToEntity($user, $parsed_result["user"], $user_scheme);
            EntityManager::applyValuesToEntity($category, $parsed_result["category"], $category_scheme);
            $parsed_result["post"]["user"] = $user;
            $parsed_result["post"]["category"] = $category;
            EntityManager::applyValuesToEntity($post, $parsed_result["post"], $post_scheme);
            $posts[] = $post;
        }
        return $posts;
    }

    function create(array $values): int
    {
        $qb = new QueryBuilder();
        $qb->insertInto(self::$table_name, $values);
        $stmt = $this->pdo->prepare($qb->getSQL());
        $values_to_bind = $qb->getParamsWithValuesWithTypes();
        if (isset($values_to_bind)) {
            foreach ($values_to_bind as $param => ["type" => $type, "value" => $value]) {
                $stmt->bindValue($param, $value, $type);
            }
        }
        $stmt->execute();
        $stmt->closeCursor();
        return $this->pdo->lastInsertId();
    }

    function update(array $values, array $where = null): void
    {
        // TODO: Implement update() method.
    }

    function delete(array $where = null): void
    {
        // TODO: Implement delete() method.
    }
}