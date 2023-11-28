<?php
namespace app\core\entity;

require_once "interfaces.php";
use app\core\interfaces\IEntity;

class Entity implements IEntity
{
    public function toAssoc(array $exclude = null): array
    {
        $assoc_result = [];
        $reflection = new \ReflectionClass($this);
        $props = $reflection->getProperties();
        foreach ($props as $prop){
            $prop_name = $prop->getName();
            $assoc_result[$prop_name] = $this->$prop_name;
        }
        // Key exclusion
        if(isset($exclude)) foreach ($exclude as $key){
            unset($assoc_result[$key]);
        }
        return $assoc_result;
    }

    public static function getPropertyKeys(object|string $object_or_class, array $exclude = null): array
    {
        $assoc_result = [];
        $reflection = new \ReflectionClass($object_or_class);
        $props = $reflection->getProperties();
        foreach ($props as $prop){
            $prop_name = $prop->getName();
            $assoc_result[$prop_name] = $prop_name;
        }
        // Key exclusion
        if(isset($exclude)) foreach ($exclude as $key){
            unset($assoc_result[$key]);
        }
        return $assoc_result;
    }
}