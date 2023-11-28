<?php

namespace app\core\query;

require_once "exception.php";

use app\core\exception\QueryBuilderException;
use \PDOStatement;
use \PDO;

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

    private ?int $method;
    private ?string $table_name;
    /**
     * @deprecated
     * @var array|null
     */
    private ?array $body = null;
    private ?array $columns = null;
    private ?array $values = null;
    private ?array $to_update = null;
    private ?array $where = null;
    private ?array $join = null;
    private int $offset = 0;
    private ?int $limit = null;
    private string $param_format = ":%s_%s";

    /**
     * Init a builder
     * @param ?int $method
     * @param ?string $table_name
     */
    public function __construct(int $method = null, string $table_name = null)
    {
        $this->method = $method;
        $this->table_name = $table_name;
    }

    /**
     * Select statement
     * @param ?array $columns
     * @return $this
     */
    public function select(array $columns = null): self {
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
    public function insertInto(string $table_name, array $values): self {
        $this->method = self::QUERY_INSERT;
        $this->table_name = $table_name;
        // Split as columns and values
        $this->columns = [];
        $this->values = $values;
        foreach ($values as $key => $value){
            $this->columns[] = $key;
        }
        return $this;
    }

    /**
     * Update statement
     * @param string $table_name
     * @param array $values
     * @return $this
     */
    public function update(string $table_name, array $values): self {
        $this->method = self::QUERY_UPDATE;
        $this->table_name = $table_name;
        // Split as columns and values
        $this->columns = [];
        $this->values = $values;
        foreach ($values as $key => $value){
            $this->columns[] = $key;
        }
        return $this;
    }

    /**
     * Delete statement
     * @param string $table_name
     * @return $this
     */
    public function deleteFrom(string $table_name): self {
        $this->method = self::QUERY_DELETE;
        $this->table_name = $table_name;
        return $this;
    }

    public function from(string $table_name): self {
        $this->table_name = $table_name;
        return $this;
    }

    /**
     * Set where conditions
     * @param ?array $where
     * @return $this
     */
    public function where(?array $where): self
    {
        $this->where = $where;
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
        $this->offset = $offset;
        $this->limit = $count;
        return $this;
    }

    /**
     * Set join
     * @param string $type
     * @param array|string $tables
     * @param array $relations
     * @return $this
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
     * Build utils
     * @return string
     * @throws QueryBuilderException
     */
    public function build(): string
    {
        return match ($this->method) {
            QueryBuilder::QUERY_SELECT => $this->buildSelectQuery(),
            QueryBuilder::QUERY_INSERT => $this->buildInsertQuery(),
            QueryBuilder::QUERY_UPDATE => $this->buildUpdateQuery(),
            QueryBuilder::QUERY_DELETE => $this->buildDeleteQuery(),
            default => throw QueryBuilderException::InvalidMethod(),
        };
    }

    /**
     * Build select utils for statement
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
        if(isset($this->join)) {
            $parts[] = $this->buildJoin();
        }
        if(isset($this->where)) {
            $parts[] = "WHERE";
            $parts[] = $this->buildWhere();
        }
        if(isset($this->limit)){
            $parts[] = "LIMIT";
            $parts[] = "$this->offset, $this->limit";;
        }
        return implode(" ", $parts) . ";";
    }

    /**
     * Build insert utils for statement
     * @throws QueryBuilderException
     * @return string
     */
    private function buildInsertQuery(): string
    {
        $parts = [
            "INSERT INTO `$this->table_name`",
            "(" . $this->buildColumns() . ")",
            "VALUES",
            "(". $this->buildParams() . ")"
        ];
        return implode(" ", $parts) . ";";
    }

    /**
     * Build delete utils for statement
     * @return string
     */
    private function buildDeleteQuery(): string
    {
        $parts = [
            "DELETE FROM `$this->table_name`"
        ];
        if(isset($this->where)) {
            $parts[] = "WHERE";
            $parts[] = $this->buildWhere();
        }
        return implode(" ", $parts) . ";";
    }

    /**
     * Build update utils for statement
     * @throws QueryBuilderException
     * @return string
     */
    private function buildUpdateQuery(): string
    {
        $parts = [
            "UPDATE `$this->table_name`",
            "SET",
            $this->buildParams()
        ];
        if(isset($this->where)){
            $parts[] = "WHERE";
            $parts[] = $this->buildWhere();
        }
        return implode(" ", $parts) . ";";
    }

    /**
     * Return joined columns
     * @return ?string
     */
    private function buildColumns(): ?string
    {
//        $example = [
//            "id",
//            "pass",
//            "author" => [
//                "id",
//                "pass"
//            ],
//            "cat" => [
//                "id",
//                "ssf"
//            ],
//            "count_saved"
//        ];
        $columns = null;
        if(isset($this->columns)) {
            foreach ($this->columns as $table_name => $column) {
                if(is_array($column)){
                    $nested_table = $column;
                    foreach ($nested_table as $nested_column) {
                        $columns[] = "`$table_name`.`$nested_column`";
                    }
                }
                else $columns[] = "`$this->table_name`.`$column`";
            }
        }
        return isset($columns) ? implode(",", $columns) : null;
    }

    /**
     * Return joined 'where' conditions or `null` if parameter where is null
     * @return string|null
     */
    private function buildWhere(): ?string
    {
        $where = null;
        if(isset($this->where)){
            $where = [];
            foreach ($this->where as $key => $value) {
                if(QueryBuilder::isOp($value))
                    $where[] = $value;
                else
                    $where[] = "`$this->table_name`.`$key`" . "=" . sprintf($this->param_format, $this->table_name, $key);
            }
            $where = implode(" ", $where);
        }
        return $where;
    }

    /**
     * Build join relations
     * @return ?string
     */
    private function buildJoin(): ?string
    {
        $join = null;
        if(isset($this->join)){
            $join = [$this->join["type"]];
            if(is_array($this->join["tables"]))
                $join[] = "(". implode(",", $this->join["tables"]) .")";
            else
                $join[] = $this->join["tables"];
            $join[] = "ON";
            $join[] = $this->buildRelations();
            $join = implode(" ", $join);
        }
        return $join;
    }

    /**
     * Build relations for join statement
     * @return string
     */
    private function buildRelations(): string
    {
        $relations = null;
        if(isset($this->join)){
            $relations = [];
            // Parse relations
            foreach ($this->join["relations"] as $left_relation => $right_relation) {
                $completed_relation = "";

                // Formatter function
                $formatter = function ($relation): string {
                    $parts = explode(".", $relation);
                    if(count($parts) > 1)
                        return "`$parts[0]`.`$parts[1]`";
                    else
                        return "`$parts[0]`";
                };
                // Formatted relation
                $completed_relation .= $formatter($left_relation) . "=" . $formatter($right_relation);
                // Add completed_relation to relations array
                $relations[] = $completed_relation;
            }
            if(count($relations) > 1)
                $relations = "(" . implode(" AND ", $relations) . ")";
            else
                $relations = implode("", $relations);
        }
        return $relations;
    }

    /**
     * Build params for <b>insert</b> or <b>update</b> statements
     * @throws QueryBuilderException
     * @return string
     */
    private function buildParams(): string
    {
        $params = [];
        if($this->method == self::QUERY_INSERT) {
            foreach ($this->columns as $key) {
                $params[] = sprintf($this->param_format, $this->table_name, $key);
            }
        }
        elseif ($this->method == self::QUERY_UPDATE) {
            foreach ($this->columns as $key) {
                $params[] = "$key" . "=" . sprintf($this->param_format, $this->table_name, $key);
            }
        }
        else
            throw new QueryBuilderException("This function use for insert and update statements only");
        return implode(",", $params);
    }

    /**
     * Check value if is operator or no
     * @param ?string $value
     * @return bool
     */
    private static function isOp(?string $value): bool
    {
        if(empty($value))
            return false;
        return match (strtoupper($value)) {
            self::OP_AND, self::OP_OR => true,
            default => false,
        };
    }

    /**
     * Bind values to statement
     * @param PDOStatement $statement
     * @return void
     * @throws QueryBuilderException
     */
    public function bindValues(PDOStatement &$statement): void
    {
        match ($this->method){
            self::QUERY_SELECT => $this->_bindValues($statement, $this->where),
            self::QUERY_INSERT => $this->_bindValues($statement, $this->values),
            self::QUERY_UPDATE => $this->_bindValues($statement, [...$this->values, ...$this->where]),
            self::QUERY_DELETE => $this->_bindValues($statement, $this->where),
            default => throw QueryBuilderException::InvalidMethod(),
        };
    }

    /**
     * Bind values to statement
     * @param PDOStatement $statement
     * @param ?array $values
     * @return void
     * @throws QueryBuilderException
     */
    private function _bindValues(PDOStatement &$statement, ?array $values): void
    {
        // Throw exception if values is null
        if(empty($values)) throw QueryBuilderException::EmptyValues();
        foreach ($values as $key => $value) {
            // Skip the operator
            if(QueryBuilder::isOp($key)) continue;

            // Select value type
            $param_type = 2;
            if(is_int($value)) $param_type = PDO::PARAM_INT;
            elseif (is_string($value)) $param_type = PDO::PARAM_STR;
            elseif (is_null($value)) $param_type = PDO::PARAM_NULL;
            else throw QueryBuilderException::UnsupportedType();

            // Bind a value
            $statement->bindValue(sprintf($this->param_format, $this->table_name, $key), $value, $param_type);
        }
    }
}