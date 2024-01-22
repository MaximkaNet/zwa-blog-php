<?php

namespace domain\categories;

use app\core\database\MysqlConfig;

class CategoriesService
{
    private MysqlConfig $db_config;
    /**
     * Service instance
     */
    private static self $instance;

    /**
     * Get service
     * @return self
     */
    public static function get(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->db_config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
    }

    /**
     * Get all categories
     * @return array
     */
    public function getAll(): array
    {
        $repo = CategoriesRepository::init($this->db_config->getPDO());
        $categories = $repo->findAll();
        return $categories ?? [];
    }

    /**
     * @param string $display_name
     * @return Category
     * @throws CategoryException Throws if category is not found
     */
    public function getByDisplayName(string $display_name): Category
    {
        $repo = CategoriesRepository::init($this->db_config->getPDO());
        $category = $repo->findOne([
            "display_name" => $display_name
        ]);
        if(empty($category)) throw new CategoryException("Category not found");
        return $category;
    }
}