<?php

namespace domain\posts;

use app\core\database\MysqlConfig;
use app\core\utils\queryBuilder\QueryBuilder;

class PostsService
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
     * @param string $title
     * @param string $content
     * @param int $user_id
     * @param int $category_id
     * @return int Returns the created post id
     */
    public function create(string $title, string $content, int $user_id, int $category_id): int
    {
        $repo = PostsRepository::init($this->db_config->getPDO());
        return $repo->create([
            "title" => $title,
            "content" => $content,
            "user_id" => $user_id,
            "category_id" => $category_id
        ]);
    }

    /**
     * Function checks if the user can perform actions with the post
     * @param int $article_id
     * @param int $user_id
     * @return bool Returns TRUE is can perform actions, FALSE otherwise
     */
    public function checkArticleAccess(int $article_id, int $user_id): bool
    {
        // Check user permissions
        $posts_repo = PostsRepository::init($this->db_config->getPDO());
        $post = $posts_repo->findOne([
            "posts" => [
                "id" => $article_id,
                QueryBuilder::OP_AND,
                "user_id" => $user_id
            ]
        ]);

        return !empty($post);
    }
}