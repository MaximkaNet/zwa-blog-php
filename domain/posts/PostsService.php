<?php

namespace domain\posts;

use app\core\database\MysqlConfig;
use app\core\exception\ApplicationException;
use app\core\utils\queryBuilder\QueryBuilder;
use domain\categories\CategoriesRepository;
use domain\categories\CategoriesService;

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

    /**
     * Get one post by id
     * @param int $id
     * @return Post|null
     */
    public function getOne(int $id): ?Post
    {
        $posts_repo = PostsRepository::init($this->db_config->getPDO());
        return $posts_repo->findById($id);
    }

    /**
     * Get all posts
     * @param int $user_id
     * @return array<Post>|null
     */
    public function getAllForUser(int $user_id): ?array
    {
        $posts_repo = PostsRepository::init($this->db_config->getPDO());
        return $posts_repo->findAll(["user_id" => $user_id]);
    }

    /**
     * Find post with empty content
     * @return Post|null
     */
    public function findEmpty(): ?Post
    {
        $posts_repo = PostsRepository::init($this->db_config->getPDO());
        return $posts_repo->findOne(["posts" => ["content" => ""]]);
    }

    /**
     * Edit title
     * @param int $post_id
     * @param string $title
     * @return void
     * @throws ApplicationException
     */
    public function editTitle(int $post_id, string $title): void
    {
        $posts_repo = PostsRepository::init($this->db_config->getPDO());
        $post = $posts_repo->findById($post_id);
        if(!$post) throw new ApplicationException("Post not found", 404);
        $posts_repo->update([
            "title" => $title
        ], [
            "id" => $post_id
        ]);
    }

    /**
     * Edit content
     * @param int $post_id
     * @param string $content
     * @return void
     * @throws ApplicationException
     */
    public function editContent(int $post_id, string $content): void
    {
        $posts_repo = PostsRepository::init($this->db_config->getPDO());
        $post = $posts_repo->findById($post_id);
        if(!$post) throw new ApplicationException("Post not found", 404);
        $posts_repo->update([
            "content" => $content
        ], [
            "id" => $post_id
        ]);
    }

    /**
     * Change category
     * @param int $post_id
     * @param int $category_id
     * @return void
     * @throws ApplicationException
     */
    public function changeCategory(int $post_id, int $category_id): void
    {
        $posts_repo = PostsRepository::init($this->db_config->getPDO());
        $categories_repo = CategoriesRepository::init($this->db_config->getPDO());
        $post = $posts_repo->findById($post_id);
        $category = $categories_repo->findById($category_id);
        if(!$post) throw new ApplicationException("Post not found", 404);
        if(!$category) throw new ApplicationException("Category not found", 404);
        $posts_repo->update([
            "category_id" => $category_id
        ], [
            "id" => $post_id
        ]);
    }

    /**
     * Delete post
     * @param int $post_id
     * @return void
     * @throws ApplicationException Throws if post is not found
     */
    public function delete(int $post_id): void
    {
        $posts_repo = PostsRepository::init($this->db_config->getPDO());
        $post = $posts_repo->findById($post_id);
        if(!$post) throw new ApplicationException("Post not found", 404);
        $posts_repo->delete(["posts" => ["id" => $post->getId()]]);
    }
}