<?php

namespace app\domain\entity;

require_once "categoryException.php";
use app\domain\exception\CategoryException;

class Category
{
    private ?int $id;
    private ?string $name;
    private ?string $display_name;

    public function __construct(int $id = null, string $name = null, string $display_name = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->display_name = $display_name;
    }

    /**
     * Return associative array of vars and values of class
     * @param ?array $exclude
     * @return array
     */
    public function toAssoc(array $exclude = null): array
    {
        $assoc_result = [];
        $reflection = new \ReflectionClass(self::class);
        $props = $reflection->getProperties();
        foreach ($props as $prop){
            $key = $prop->getName();
            $assoc_result[$key] = $this->$key;
        }
        // Key exclusion
        if(isset($exclude))
            $assoc_result = array_diff($assoc_result, $exclude);
        return $assoc_result;
    }

    /**
     * Return an array of column names
     * @return string[]
     */
    public static function getPropertyKeys(array $exclude = null): array
    {
        $reflection = new \ReflectionClass(self::class);
        $props = $reflection->getProperties();
        $property_names = [];
        foreach ($props as $prop){
            $property_names[] = $prop->getName();
        }
        if(isset($exclude))
            $property_names = array_diff($property_names, $exclude);
        return $property_names;
    }

    /**
     * Return id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set model id
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->display_name;
    }

    /**
     * Name will be set in correct format - <i>new-category</i>
     * @param string $new_name
     * @throws CategoryException
     */
    public function setName(string $new_name): void
    {
        $new_name = strtolower($new_name);
        if(preg_match("/\s/", $new_name))
            throw CategoryException::IncorrectFormat();
        $this->name = $new_name;
    }

    /**
     * @param string $new_display_name
     */
    public function changeDisplayName(string $new_display_name): void
    {
        $this->display_name = $new_display_name;
    }
}