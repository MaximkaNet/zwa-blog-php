<?php

namespace app\core\interfaces;

interface IEntity
{
    /**
     * Return associative pair(variable key, value) array
     * @param ?array $exclude
     * @return array
     */
    function toAssoc(?array $exclude): array;

    /**
     * Return property keys
     * @return string[]
     */
    static function getPropertyKeys(object|string $object_or_class, ?array $exclude): array;
}