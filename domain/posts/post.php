<?php

namespace app\domain\entity;
require_once "../users/user.php";
require_once "../categories/category.php";

use ReflectionClass;
use ReflectionException;

class Post
{
    private ?int $id;
    private ?string $title;
    private ?string $content;
    private ?int $rating;
    private ?int $count_saved;
    private ?User $user;
    private ?Category $category;
    private bool $is_edited;

    /**
     * Init a post
     * @param int|null $id
     * @param string|null $title
     * @param string|null $content
     * @param int|null $rating
     * @param int|null $count_saved
     * @param User|null $author
     * @param Category|null $category
     * @param bool $is_edited
     */
    public function __construct(
        int $id = null,
        string $title = null,
        string $content = null,
        int $rating = null,
        int $count_saved = null,
        User $author = null,
        Category $category = null,
        bool $is_edited = false
    )
    {
        $this->id = $id;
        $this->user = $author;
        $this->category = $category;
        $this->title = $title;
        $this->content = $content;
        $this->rating = $rating;
        $this->count_saved = $count_saved;
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
                $property_names[$prop_name] = $prop_name;
        }
        if(isset($exclude))
            foreach ($exclude as $item){
                $key = array_search($item, $property_names);
                unset($property_names[$key]);
            }
        return $property_names;
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return ?int
     */
    public function getCountSaved(): ?int
    {
        return $this->count_saved;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @return ?int
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param string $new_title
     * @return void
     */
    public function editTitle(string $new_title):void
    {
        $this->title = $new_title;
        $this->is_edited = true;
    }

    /**
     * @param string $new_content
     * @return void
     */
    public function editContent(string $new_content): void
    {
        $this->content = $new_content;
        $this->is_edited = true;
    }

    /**
     * @param Category $new_category
     * @return void
     */
    public function changeCategory(Category $new_category): void
    {
        $this->category = $new_category;
        $this->is_edited = true;
    }

    /**
     * Check post status
     * @return bool
     */
    public function isEdited(): bool
    {
        return $this->is_edited;
    }
}