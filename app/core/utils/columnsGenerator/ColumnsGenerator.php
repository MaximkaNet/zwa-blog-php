<?php

namespace app\core\utils\columnsGenerator;

/**
 * Columns generator
 */
class ColumnsGenerator
{
    private ?string $table;
    private ?array $columns;
    private ?array $rules = null;

    public function __construct(string $table = null, array $columns = null)
    {
        $this->table = $table;
        $this->columns = $columns;
    }

    /**
     * Set main table
     * @param string $table
     */
    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    /**
     * @return string|null Return main table name
     */
    public function getTable(): ?string
    {
        return $this->table;
    }

    /**
     * Set main columns
     * @param array $columns
     */
    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

    /**
     * @return array|null Return main columns
     */
    public function getColumns(): ?array
    {
        return $this->columns;
    }

    /**
     * Set rules for scheme generation
     * @param array $rules
     */
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    /**
     * Generate associative scheme
     * @return array
     */
    public function generateSchemeAssoc(): array
    {
        // Step 0. check if rules is not null
        if (empty($this->rules)) return $this->columns;
        // Step 1. init columns
        $scheme = $this->columns;
        // Step 2. handle rules
        foreach ($this->rules as $overwrite => $rule) {
            $overwrite_candidate = array_search($overwrite, $scheme);
            // Step to next rule if object to overwrite not found or not exists
            if(!$overwrite_candidate) continue;
            if(is_array($rule)){
                // Delete column from scheme
                unset($scheme[$overwrite_candidate]);
                // Add nested columns to scheme
                $nested_table = array_key_first($rule);
                $nested_cols = $rule[$nested_table];
                $scheme[$nested_table] = $nested_cols;
            }
            else {
                $scheme[$overwrite_candidate] = $rule;
            }
        }
        // Step 3. return associative array
        return $scheme;
    }

    /**
     * Check rules
     * @return bool Return <b>true</b> if rules has been set and <b>false</b>, if rules is empty
     */
    public function isNested(): bool
    {
        return empty($this->rules);
    }
}