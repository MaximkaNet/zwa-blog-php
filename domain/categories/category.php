<?php

namespace app\domain\categories;

require_once "../../core/entity.php";
use app\core\entity\Entity;

require_once "categoryException.php";

class Category extends Entity
{
    protected ?int $id;
    protected ?string $name;
    protected ?string $display_name;

    public function __construct(
        int $id = null,
        string $name = null,
        string $display_name = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->display_name = $display_name;
    }

    public static function getPropertyKeys(array $exclude = null): array
    {
        return self::_getPropertyKeys(new Category(), $exclude);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
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