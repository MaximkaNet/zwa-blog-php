<?php

namespace app\core\utils\queryBuilder;

use PDO;

class QueryBuilder
{
    /* Query types */
    const QUERY_SELECT = 1;
    const QUERY_INSERT = 2;
    const QUERY_UPDATE = 4;
    const QUERY_DELETE = 8;

    /* Operators */
    const OP_AND = "AND";
    const OP_OR = "OR";

    /* Joins */
    const INNER_JOIN = "INNER JOIN";
    const LEFT_JOIN = "LEFT JOIN";
    const RIGHT_JOIN = "RIGHT JOIN";
    const CROSS_JOIN = "CROSS JOIN";

    /**
     * Query statement
     */
    private ?int $method;

    /**
     * Table name
     */
    private ?string $table_name;

    /**
     * Columns
     */
    private array|string|null $columns = null;

    /**
     * The SET in update statement
     */
    private ?array $set = null;

    /**
     * Where
     */
    private ?array $where = null;

    /**
     * Params. Will be used in bind methods
     */
    private ?array $params = null;

    /**
     * Join
     */
    private ?array $join = null;

    /**
     * Limit
     */
    private ?array $limit = null;

    /**
     * Param format
     */
    private string $param_format = ":%s_%s";

    /**
     * Init a builder
     * @param int|null $method
     * @param string|null $table_name
     */
    public function __construct(int $method = null, string $table_name = null)
    {
        $this->method = $method;
        $this->table_name = $table_name;
    }

    /**
     * Create columns provider
     * @return ColumnsProvider
     */
    public static function createColumnsProvider(): ColumnsProvider
    {
        return new ColumnsProvider();
    }

    /**
     * Parse query result before using ColumnsProvider. Function returns array in format:
     * ```
     *  $parsed_result = [
     *      "entity_or_table_name" => [
     *          "property_1" => "value_1",
     *          "property_2" => "value_2",
     *          // ...
     *      ],
     *      "entity_or_table_name_2" => [
     *          "property_1" => "value_1",
     *          "property_2" => "value_2",
     *          // ...
     *      ]
     *      // ...
     *  ];
     * ```
     * @param array|false $subject
     * @param bool $stack
     * @return array
     */
    public static function parseQueryResult(array|false $subject, bool $stack = false): array
    {
        if (!$subject) {
            return [];
        }
        $result = [];
        if ($stack) {
            foreach ($subject as $row) {
                $result[] = ColumnsProvider::parse($row);
            }
        } else {
            $result = ColumnsProvider::parse($subject);
        }
        return $result;
    }

    /**
     * Select statement
     * @param array|string|null $columns
     * @return $this
     */
    public function select(array|string $columns = null): self
    {
        $this->method = self::QUERY_SELECT;
        $this->columns = $columns;
        return $this;
    }

    /**
     * Insert into statement
     * @param string $table_name
     * @param array $values
     * @return $this
     */
    public function insertInto(string $table_name, array $values): self
    {
        $this->method = self::QUERY_INSERT;
        $this->table_name = $table_name;
        // Split as columns and values
        $this->columns = [];
        $this->params = [];
        foreach ($values as $key => $value) {
            $this->columns[] = $key;
            $param_str = ":$table_name" . "_" . $key;
            $this->params[$param_str] = $value;
        }
        return $this;
    }

    /**
     * Update statement
     * @param string $table_name
     * @param array $values
     * @return $this
     */
    public function update(string $table_name, array $values): self
    {
        $this->method = self::QUERY_UPDATE;
        $this->table_name = $table_name;
        // Split as columns and values
        $this->params = [];
        $this->set = $values;
        return $this;
    }

    /**
     * Delete statement
     * @param string $table_name
     * @return $this
     */
    public function deleteFrom(string $table_name): self
    {
        $this->method = self::QUERY_DELETE;
        $this->table_name = $table_name;
        return $this;
    }

    /**
     * Set from statement
     * @param string $table_name
     * @return $this
     */
    public function from(string $table_name): self
    {
        $this->table_name = $table_name;
        return $this;
    }

    /**
     * Set where conditions
     * @return $this
     */
    public function where(?array $where): self
    {
        if (empty($where)) {
            $this->where = null;
        } else {
            $this->where = $where;
        }
        return $this;
    }

    /**
     * Set the limit and offset of rows
     * @param int $offset
     * @param int $count
     * @return $this
     */
    public function limit(int $offset, int $count): self
    {
        $this->limit["offset"] = $offset;
        $this->limit["limit"] = $count;
        return $this;
    }

    /**
     * Set join
     * @param string $type
     * @param array|string $tables
     * @param array $relations
     * @return $this
     * @deprecated
     */
    public function join(string $type, array|string $tables, array $relations): self
    {
        $this->join = [
            "type" => $type,
            "tables" => $tables,
            "relations" => $relations
        ];
        return $this;
    }

    /**
     * Inner join
     * @param string $source
     * @param string $source_column
     * @param string $target
     * @param string $target_column
     * @return $this
     */
    public function innerJoin(
        string $source,
        string $source_column,
        string $target,
        string $target_column
    ): self {
        $this->join[] = [
            "type" => self::INNER_JOIN,
            "source" => [
                "table" => $source,
                "column" => $source_column
            ],
            "target" => [
                "table" => $target,
                "column" => $target_column
            ]
        ];
        return $this;
    }

    /**
     * Set offset
     * @param int $offset
     * @return $this
     */
    public function setFirstResults(int $offset): self
    {
        $this->limit["offset"] = $offset;
        return $this;
    }

    /**
     * Set limit
     * @param int $count
     * @return $this
     */
    public function setMaxResults(int $count): self
    {
        $this->limit["limit"] = $count;
        return $this;
    }

    /**
     * Build query
     * @return string
     */
    public function getSQL(): string
    {
        return match ($this->method) {
            QueryBuilder::QUERY_SELECT => $this->buildSelectQuery(),
            QueryBuilder::QUERY_INSERT => $this->buildInsertQuery(),
            QueryBuilder::QUERY_UPDATE => $this->buildUpdateQuery(),
            QueryBuilder::QUERY_DELETE => $this->buildDeleteQuery()
        };
    }

    /**
     * Build select query for statement
     * @return string
     */
    private function buildSelectQuery(): string
    {
        $parts = [
            "SELECT",
            $this->buildColumns() ?? "*",
            "FROM",
            "`$this->table_name`"
        ];
        if (isset($this->join)) {
            $parts[] = $this->buildJoin();
        }
        if (isset($this->where)) {
            $parts[] = "WHERE";
            $parts[] = $this->buildWhere();
        }
        if (isset($this->limit["limit"])) {
            $limit_str = "LIMIT " . $this->limit["limit"];
            if (isset($this->limit["offset"])) {
                $limit_str .= " OFFSET " . $this->limit["offset"];
            }
            $parts[] = $limit_str;
        }
        return implode(" ", $parts) . ";";
    }

    /**
     * Build insert query for statement
     * @return string
     */
    private function buildInsertQuery(): string
    {
        $parts = [
            "INSERT INTO `$this->table_name`",
            "(" . $this->buildColumns() . ")",
            "VALUES",
            "(" . implode(", ", array_keys($this->params)) . ")"
        ];
        return implode(" ", $parts) . ";";
    }

    /**
     * Build delete query for statement
     * @return string
     */
    private function buildDeleteQuery(): string
    {
        $parts = [
            "DELETE FROM `$this->table_name`"
        ];
        if (isset($this->where)) {
            $parts[] = "WHERE";
            $parts[] = $this->buildWhere();
        }
        return implode(" ", $parts) . ";";
    }

    /**
     * Build update query for statement
     * @return string
     */
    private function buildUpdateQuery(): string
    {
        $parts = [
            "UPDATE `$this->table_name`",
            "SET",
            $this->buildUpdateSet()
        ];
        if (isset($this->where)) {
            $parts[] = "WHERE";
            $parts[] = $this->buildWhere();
        }
        return implode(" ", $parts) . ";";
    }

    /**
     * Return joined columns
     * @return string|null
     */
    private function buildColumns(): ?string
    {
        if (empty($this->columns)) {
            return null;
        }
        $cols_arr = [];
        foreach ($this->columns as $table => $column) {
            if (is_array($column)) {
                if (is_string($table)) {
                    // Handle nested columns
                    $nested_columns = $this->columns[$table];
                    foreach ($nested_columns as $nest_column) {
                        if (is_array($nest_column)) {
                            // Handle alias
                            $source_column = key($nest_column);
                            $column_as = $nest_column[$source_column];
                            $cols_arr[] = "`$table`.`$source_column` as `$column_as`";
                        } else {
                            // Without alias
                            $cols_arr[] = "`$table`.`$nest_column`";
                        }
                    }
                } else {
                    // Simple alias
                    $source_column = key($column);
                    $column_as = $column[$source_column];
                    $cols_arr[] = "`$source_column` as `$column_as`";
                }
            } else {
                $cols_arr[] = "`$column`";
            }
        }
        return implode(",", $cols_arr);
    }

    /**
     * Return joined 'where' conditions or `null` if where property is null
     * @return string|null
     */
    private function buildWhere(): ?string
    {
        if (empty($this->where)) {
            return null;
        }
        $where = [];
        foreach ($this->where as $key => $value) {
            // Process nested columns
            if (is_array($value)) {
                foreach ($value as $nest_key => $nest_value) {
                    if (QueryBuilder::isOp($nest_value)) {
                        $where[] = $nest_value;
                    } else {
                        $param_str = sprintf(
                            $this->param_format,
                            $key,
                            $nest_key
                        );
                        $where[] = "`$key`.`$nest_key` = " . $param_str;
                        $this->params[$param_str] = $nest_value;
                    }
                }
            } else {
                if (QueryBuilder::isOp($value)) {
                    $where[] = $value;
                } else {
                    $where[] = "`$key` = :" . $key;
                    $this->params[":$key"] = $value;
                }
            }
        }
        return implode(" ", $where);
    }

    /**
     * Build join
     * @return string|null
     */
    private function buildJoin(): ?string
    {
        if (empty($this->join)) {
            return null;
        }
        $joins = [];
        foreach ($this->join as ["type" => $type, "source" => $source, "target" => $target]) {
            $source_table = $source["table"];
            $source_column = $source["column"];
            $target_table = $target["table"];
            $target_column = $target["column"];
            $joins[] = "$type `$target_table` ON `$source_table`.`$source_column` = `$target_table`.`$target_column`";
        }
        return implode(" ", $joins);
    }

    /**
     * Build update SET
     * @return string
     */
    private function buildUpdateSet(): string
    {
        if (empty($this->set)) {
            return "";
        }
        $set_props = [];
        foreach ($this->set as $key => $value) {

            $param_str = ":set_$key";
            $this->params[$param_str] = $value;
            $set_props[] = "`$key` = " . $param_str;
        }
        return implode(", ", $set_props);
    }

    /**
     * Check value if is operator or no
     * @param string|null $value
     * @return bool
     */
    private static function isOp(?string $value): bool
    {
        if (empty($value)) {
            return false;
        }
        return match (strtoupper($value)) {
            self::OP_AND, self::OP_OR => true,
            default => false,
        };
    }

    /**
     * Return the values that will be bound in the query
     * @return array|null
     * @throws QueryBuilderException
     */
    public function getParamsWithValuesWithTypes(): ?array
    {
        if (empty($this->params)) {
            return null;
        }
        $parsed_params = [];
        foreach ($this->params as $param => $value) {
            // Select value type
            $param_type = 2;
            if (is_int($value)) {
                $param_type = PDO::PARAM_INT;
            } elseif (is_string($value)) {
                $param_type = PDO::PARAM_STR;
            } elseif (is_null($value)) {
                $param_type = PDO::PARAM_NULL;
            } else {
                throw QueryBuilderException::UnsupportedType();
            }
            // Add value to bind
            $parsed_params[$param]["type"] = $param_type;
            $parsed_params[$param]["value"] = $value;
        }
        return $parsed_params;
    }

    /**
     * Map query parameters
     * @return array|null
     */
    public function getParamsWithValues(): ?array
    {
        return $this->params;
    }
}