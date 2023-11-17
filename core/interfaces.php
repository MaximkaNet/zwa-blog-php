<?php

namespace app\core\interfaces;

interface IModel {
    /**
     * Get model by id
     * @param int $id
     * @return mixed
     */
    public static function getById(int $id);

    /**
     * Get all models
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public static function getAll(int $offset = 0, int $limit = 0): array;

    /**
     * Insert already defined model into database
     * @return mixed
     */
    public function create();

    /**
     * Delete row from database
     * @return mixed
     */
    public function destroy();

    /**
     * Check model in database
     * @return bool
     */
    public function exists(): bool;
}