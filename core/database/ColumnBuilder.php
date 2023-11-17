<?php

namespace app\core\database;

define("ATTR_NULLABLE", "NULLABLE");
define("ATTR_PRIMARY_KEY", "PRIMARY_KEY");
define("ATTR_AUTO_INCREMENT", "AUTO_INCREMENT");
define("ATTR_UNIQUE", "UNIQUE");
define("ATTR_DEFAULT", "DEFAULT");

class ColumnBuilder {

    /**
     * Required
     * @type string
     * @var $name
     */
    private $name;

    /**
     * Required
     * @type string
     * @var $type
     */
    private $type;

    /**
     * @var array
     */
    private $attributes;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->attributes[ATTR_NULLABLE] = "NULL";
    }

    public function __toString(): string
    {
        return "`$this->name` $this->type " . implode(" ", $this->attributes);
    }

    /**
     * Set null
     * @return $this
     */
    public function notNull(): ColumnBuilder
    {
        $this->attributes[ATTR_NULLABLE] = "NOT NULL";
        return $this;
    }

    /**
     * Set primary key
     * @return $this
     */
    public function primaryKey(): ColumnBuilder
    {
        $this->attributes[ATTR_PRIMARY_KEY] = "PRIMARY KEY";
        return $this;
    }

    /**
     * Set auto increment attribute
     * @return $this
     */
    public function autoIncrement(): ColumnBuilder {
        $this->attributes[ATTR_AUTO_INCREMENT] = "AUTO_INCREMENT";
        return $this;
    }

    /**
     * Set unique attribute
     * @return $this
     */
    public function unique(): ColumnBuilder {
        $this->attributes[ATTR_UNIQUE] = "UNIQUE";
        return $this;
    }

    /**
     * Set default value
     * @param string $value
     * @param string|null $type s - string i - integer d - float|double
     * @return $this
     */
    public function default(string $value, string $type = null): ColumnBuilder
    {
        $this->attributes[ATTR_DEFAULT] = "DEFAULT";
        if ($type !== null) switch ($type) {
            case 's':
                $this->attributes[ATTR_DEFAULT] .= " '$value'";
                break;
            case 'i' || 'd':
                $this->attributes[ATTR_DEFAULT] .= " $value";
                break;
            default:
                trigger_error("Unknown type", E_USER_WARNING);
                break;
        }
        else $this->attributes[ATTR_DEFAULT] .= " $value";
        return $this;
    }

    /**
     * Build a column definition
     * @param string $name
     * @param string $type
     * @return self
     */
    public static function build(string $name, string $type): ColumnBuilder
    {
        return new ColumnBuilder($name, $type);
    }
}
