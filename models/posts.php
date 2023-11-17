<?php

namespace app\models;

use app\core\interfaces\IModel;
use app\models\UserModel;
use \mysqli;

use app\dto\PostDto;

class PostModel implements IModel{
    public static function getById(int $id): PostModel
    {
        $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB);
        $query = "SELECT ";
        $query .= "`posts`.`id`, `posts`.`title`, `posts`.`content`, `posts`.`date`, `posts`.`rating`, `posts`.`count_saved`, ";
        $query .= "`users`.`id`, `users`.`first_name`, `users`.`last_name`, `users`.`avatar` ";
        $query .= "FROM `posts` ";
        $query .= "INNER JOIN `users` ON `posts`.`user_id` = `users`.`id` ";
        $query .= "WHERE `posts`.`id` = ?";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) trigger_error("Error preparing, maybe incorrect query", E_CORE_ERROR);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($post_id, $post_title, $post_content, $post_date, $post_rating,
            $post_count_saved, $user_id, $user_first_name, $user_last_nama, $user_avatar);
        $stmt->fetch();
        $user = new UserModel($user_id, null, null, $user_first_name, $user_last_nama, $user_avatar);
        return new PostModel($post_id, $post_title, $post_content, $post_date, $user, $post_rating, $post_count_saved);
    }
    public static function getAll(int $offset = 0, int $limit = 10): array
    {

    }
    public function create() {

    }
    public function destroy() {

    }
    public function exists(int $id): bool{

    }
}