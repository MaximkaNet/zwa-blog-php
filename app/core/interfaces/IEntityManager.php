<?php

namespace app\core\interfaces;

interface IEntityManager
{
    /**
     * Apply values to entity
     * @param object $entity
     * @param array $values
     * @param array $scheme
     * @return void
     */
    public static function applyValuesToEntity(
        object &$entity,
        array $values,
        array $scheme
    ): void;
}