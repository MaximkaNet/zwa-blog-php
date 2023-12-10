<?php

namespace app\domain\categories;

require_once "../../core/databaseConfig.php";
use app\core\DatabaseConfiguration;
require_once "../../core/exception.php";
use app\core\exception\ApplicationException;
require_once "../../core/utils/queryBuilder/queryBuilder.php";
use app\core\utils\queryBuilder\QueryBuilder;
use PDO;

class CategoryDataSource
{
    private PDO $pdo;
    public const TABLE_NAME = "categories";
    public function __construct(DatabaseConfiguration $config)
    {
        $this->pdo = new PDO($config->toDsn("mysql"), $config->getUsername(), $config->getPassword());
    }

    /**
     * Select columns from table
     * @param array|null $columns
     * @param array|null $options
     * @return array|null
     * @throws ApplicationException
     */
    public function select(?array $columns, ?array $options = null): ?array
    {
        try {
            $query_builder = new QueryBuilder();
            $query_builder->select($columns);
            $query_builder->from(self::TABLE_NAME);
            if(isset($options["where"])) $query_builder->where($options["where"]);
            if(isset($options["limit"])) $query_builder->limit($options["limit"][0], $options["limit"][1]);
            $stmt = $this->pdo->prepare($query_builder->build());
            // Bind params if exists
            $values_to_bind = $query_builder->getValuesToBind();
            if(isset($values_to_bind)) foreach ($values_to_bind as $param => $props)
            {
                $stmt->bindParam($param, $props["value"], $props["type"]);
            }
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result) return null;
            if(count($result) > 1)
                return $result;
            return $result[0];
        } catch (\Exception $e) {
            throw ApplicationException::BadQuery($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Insert values into table
     * @param array $values Values to insert
     * @return int The last insert id
     * @throws ApplicationException
     */
    public function insert(array $values): int
    {
        try {
            $query_builder = new QueryBuilder();
            $query_builder->insertInto(self::TABLE_NAME, $values);
            $stmt = $this->pdo->prepare($query_builder->build());
            $values_to_bind = $query_builder->getValuesToBind();
            if (isset($values_to_bind)) foreach ($values_to_bind as $param => $props) {
                $stmt->bindParam($param, $props["value"], $props["type"]);
            }
            $stmt->execute();
            return (int)$this->pdo->lastInsertId();
        } catch (\Exception $e) {
            throw ApplicationException::BadQuery($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update the table with values
     * @param array $values Values to be updated
     * @param array|null $options
     * @return int Count rows affected
     * @throws ApplicationException
     */
    public function update(array $values, ?array $options = null): int
    {
        try {
            $query_builder = new QueryBuilder();
            $query_builder->update(self::TABLE_NAME, $values);
            if(isset($options["where"])) $query_builder->where($options["where"]);
            $stmt = $this->pdo->prepare($query_builder->build());
            // Bind values into query
            $values_to_bind = $query_builder->getValuesToBind();
            if(isset($values_to_bind)) foreach ($values_to_bind as $param => $props) {
                $stmt->bindParam($param, $props["value"], $props["type"]);
            }
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            throw ApplicationException::BadQuery($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete model from
     * @param array|null $options
     * @return int Return count rows affected
     * @throws ApplicationException
     */
    public function delete(?array $options = null): int
    {
        try {
            $query_builder = new QueryBuilder();
            $query_builder->deleteFrom(self::TABLE_NAME);
            if(isset($options["where"])) $query_builder->where($options["where"]);
            $stmt = $this->pdo->prepare($query_builder->build());
            // Bind values into query
            $values_to_bind = $query_builder->getValuesToBind();
            if(isset($values_to_bind)) foreach ($values_to_bind as $param => $props) {
                $stmt->bindParam($param, $props["value"], $props["type"]);
            }
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            throw ApplicationException::BadQuery($e->getMessage(), $e->getCode());
        }
    }
}