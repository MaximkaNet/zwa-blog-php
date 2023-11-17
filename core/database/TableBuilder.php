<?php
namespace app\core\database;
class TableBuilder {
    /**
     * Required
     * @var string
     */
    private $name;

    /**
     * @type array<ColumnBuilder>
     * @var $columns
     */
    private $columns;

    /**
     * Set table name
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Define columns according to schema
     * @param array $schema
     * @return $this
     */
    public function define(array $schema): self
    {
        foreach ($schema as $column){
            $this->columns[] = $column;
        }
        return $this;
    }

    /**
     * Build a query to create a table
     * @return string
     */
    public function build(): string
    {
        return "CREATE TABLE IF NOT EXISTS `$this->name`(" . implode(", ", $this->columns) . ");";
    }
}
