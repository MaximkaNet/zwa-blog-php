<?php

namespace app\core\utils\queryBuilder;

class ColumnsProvider
{
    private array $tables;

    private ?string $current_table = null;

    private Join $join;

    /**
     * Get table name in current state.
     * @return string|null
     */
    public function getCurrentTable(): ?string
    {
        return $this->current_table;
    }

    /**
     * Get relations builder
     * @return Join
     */
    public function buildRelations(): Join
    {
        if (isset($this->join)) {
            return $this->join;
        } else {
            $this->join = new Join();
        }
        return $this->join;
    }

    /**
     * Add new table and start state. If the table name already exists, it will not be overwritten.
     * @param string $table_name
     * @return $this
     */
    public function addTable(string $table_name): self
    {
        if (isset($this->tables)) {
            if (!key_exists($table_name, $this->tables)) {
                $this->tables[$table_name] = null;
                $this->current_table = $table_name;
            }
        } else {
            $this->tables[$table_name] = null;
            $this->current_table = $table_name;
        }
        return $this;
    }

    /**
     * Set entity name, but state must be initialized.
     * @param string $entity_name
     * @return $this
     */
    public function setEntityName(string $entity_name): self
    {
        if (isset($this->current_table)) {
            $this->tables[$this->current_table]["entity"] = $entity_name;
        }
        return $this;
    }

    /**
     * Set entity name. This method does independent on the state.
     * After calling this method, the state will not be changed.
     * @param string $table_name
     * @param string $entity_name
     * @return $this
     */
    public function setEntityNameFor(string $table_name, string $entity_name): self
    {
        $this->tables[$table_name]["entity"] = $entity_name;
        return $this;
    }

    /**
     * Set columns for table in current state.
     * @param array $columns
     * @return $this
     */
    public function setColumns(array $columns): self
    {
        if (isset($this->current_table)) {
            $this->tables[$this->current_table]["columns"] = $columns;
        }
        return $this;
    }

    /**
     * Set columns for table. This method does independent on the state.
     * After calling this method, the state will not be changed.
     * @param string $table_name
     * @param array $columns
     * @return $this
     */
    public function setColumnsFor(string $table_name, array $columns): self
    {
        $this->tables[$table_name]["columns"] = $columns;
        return $this;
    }

    /**
     * Generate columns scheme.
     * @return array Returns list of columns
     */
    public function generateColumns(): array
    {
        if (empty($this->tables)) {
            return [];
        }
        $columns = [];
        foreach ($this->tables as $table => $props) {
            $table_or_entity = $props["entity"] ?? $table;
            if (isset($props["columns"])) {
                foreach ($props["columns"] as $column) {
                    if (is_array($column)) {
                        // Process custom alias
                        $source_column = key($column);
                        $alias_column = $column[$source_column];
                        // Generate alias for ColumnsProvider
                        $provider_alias = $table_or_entity . "_" . $alias_column;
                        $columns[$table][] = [$source_column => $provider_alias];
                    } else {
                        // Generate alias for ColumnsProvider
                        $provider_alias = $table_or_entity . "_" . $column;
                        $columns[$table][] = [$column => $provider_alias];
                    }
                }
            }
        }
        return $columns;
    }

    /**
     * Parse result.
     * @param array $subject
     * @return array
     */
    public static function parse(array $subject): array
    {
        $parsed_result = [];
        foreach ($subject as $column => $value) {
            if (preg_match("/^(?<entity>[a-z0-9]+)_(?<property>\w+)/", $column, $matches)) {
                $parsed_result[$matches["entity"]][$matches["property"]] = $value;
            }
        }
        return $parsed_result;
    }
}