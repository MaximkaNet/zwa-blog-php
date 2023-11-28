<?php

namespace app\domain\entity;
require_once "../users/user.php";

use ReflectionClass;
use ReflectionException;
class Comment
{
    private ?int $id;
    private ?User $author;
    private ?string $content;
//    private ?Comment $parent;
    private bool $is_edited;

    public function __construct(
        int $id = null,
        User $author = null,
        string $content = null,
//        Comment $parent = null,
        bool $is_edited = false
    )
    {
        $this->id = $id;
        $this->author = $author;
        $this->content = $content;
//        $this->parent = $parent;
        $this->is_edited = $is_edited;
    }

    /**
     * Return associative array of properties
     * @param array|null $exclude
     * @param bool $nested_exclude
     * @return array
     * @throws ReflectionException
     */
    public function toAssoc(array $exclude = null, bool $nested_exclude = true): array
    {
        $assoc_result = [];
        $reflection = new ReflectionClass(self::class);
        $props = $reflection->getProperties();
        foreach ($props as $prop){
            $prop_type = $prop->getType()->getName();
            $prop_name = $prop->getName();
            if(self::isClass($prop_type)) {
                if(empty($this->$prop_name))
                    $assoc_result[$prop_name] = null;
                else
                    if($nested_exclude)
                        $assoc_result[$prop_name] = $this->$prop_name->toAssoc($exclude);
                    else
                        $assoc_result[$prop_name] = $this->$prop_name->toAssoc();
            }
            else
                $assoc_result[$prop_name] = $this->$prop_name;
        }
        if(isset($exclude))
            foreach ($exclude as $item) {
                unset($assoc_result[$item]);
            }
        return $assoc_result;
    }

    /**
     * Return property names
     * @param array|null $exclude
     * @param bool $nested_exclude
     * @return array
     * @throws ReflectionException
     */
    public static function getPropertyKeys(array $exclude = null, bool $nested_exclude = true): array
    {
        $reflection = new ReflectionClass(self::class);
        $props = $reflection->getProperties();
        $property_names = [];
        foreach ($props as $prop){
            $prop_type = $prop->getType()->getName();
            $prop_name = $prop->getName();
            if(self::isClass($prop_type)) {
                $nested_reflection = new ReflectionClass($prop_type);
                $getNestedKeys = $nested_reflection->getMethod('getPropertyKeys')->getClosure();
                if($nested_exclude)
                    $property_names[$prop_name] = $getNestedKeys($exclude);
                else
                    $property_names[$prop_name] = $getNestedKeys();
            }
            else
                $property_names[] = $prop_name;
        }
        if(isset($exclude))
            foreach ($exclude as $item){
                $key = array_search($item, $property_names);
                unset($property_names[$key]);
            }
        return $property_names;
    }

    /**
     * Check if type is class
     * @param string $type
     * @return bool
     */
    public static function isClass(string $type): bool
    {
        return match ($type){
            "string", "int", "float", "bool" => false,
            default => true
        };
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

//    public function getParentComment(): Comment
//    {
//        return $this->parent;
//    }

    /**
     * @param string $content
     */
    public function editContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Check comment status
     * @return bool
     */
    public function isEdited(): bool
    {
        return $this->is_edited;
    }
}