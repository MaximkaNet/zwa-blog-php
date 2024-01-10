<?php

namespace app\core\database;

use app\core\exception\EntityManagerException;
use app\core\interfaces\IEntityManager;
use ReflectionClass;

class EntityManager implements IEntityManager
{
    /**
     * Apply values to entity
     * @param object $entity
     * @param array $values
     * @param array $scheme
     * @return void
     * @throws EntityManagerException Throws EntityManagerException if the property is not found
     */
    public static function applyValuesToEntity(
        object &$entity,
        array $values,
        array $scheme,
    ): void {
        $reflection_class = new ReflectionClass($entity);
        foreach ($scheme as $db_column => $ent_property) {
            if ($reflection_class->hasProperty($ent_property)) {
                $prop = $reflection_class->getProperty($ent_property);
                if (isset($values[$db_column])) {
                    $prop->setValue($entity, $values[$db_column]);
                }
            } else {
                throw new EntityManagerException(
                    "Property $ent_property is not found in " . $reflection_class->getName()
                );
            }
        }
    }
}