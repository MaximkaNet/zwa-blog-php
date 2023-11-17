<?php
namespace app\models;

require_once "core\interfaces.php";

use app\core\interfaces\IModel;
use \mysqli;

class UserModel implements IModel{
    protected $id;
    protected $email;
    protected $password;
    protected $first_name;
    protected $last_name;
    /**
     * Contains the name of the file
     */
    protected $avatar;


    public function __construct(
        int $id = null,
        string $email= null,
        string $password= null,
        string $first_name= null,
        string $last_name= null,
        string $avatar_filename= null
    ){
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->avatar = $avatar_filename;
    }

    /**
     * @param int $id
     * @return UserModel|null
     */
    public static function getById(int $id): UserModel
    {
        $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB);
        $query = "SELECT `id`, `email`, `password`, `first_name`, `last_name`, `avatar` FROM `users` WHERE `id` = ?";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) trigger_error("Error preparing, maybe incorrect query", E_CORE_ERROR);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($id, $email, $password, $first_name, $last_name, $avatar_filename);
        $stmt->fetch();

        $user = new UserModel($id, $email, $password, $first_name, $last_name, $avatar_filename);
        $mysqli->close();

        return $user;
    }
    public static function getAll(int $offset = 0, int $limit = 0): array
    {
        $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD,DB);
        $query = "SELECT `id`, `email`, `password`, `first_name`, `last_name`, `avatar_sdf` FROM `users`";
        $query .= ($limit != 0 ? " LIMIT ? OFFSET ?" : "");
        $stmt = $mysqli->prepare($query);
        if(!$stmt){
            trigger_error("Error preparing, maybe incorrect query", E_CORE_ERROR);
            return [];
        }
        if($limit != 0)
            $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        $stmt->bind_result($id, $email, $password, $first_name, $last_name, $avatar_filename);
        $models = [];
        while ($stmt->fetch()){
            $models[] = new UserModel($id, $email, $password, $first_name, $last_name, $avatar_filename);
        }
        $mysqli->close();
        return $models;
    }
    public function create()
    {
        $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD,DB);
        $query = "INSERT INTO `users` (`email`, `password`, `first_name`, `last_name`, `avatar_filename`) values(?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssss', $this->email, $this->password, $this->first_name, $this->last_name, $this->avatar);
        $stmt->execute();
        $mysqli->close();
    }
    public function destroy()
    {
        $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD,DB);
        $query = "DELETE FROM `users` WHERE `id` = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $mysqli->close();
    }
    public function exists(): bool
    {
        $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD,DB);
        $query = "SELECT `id` FROM `users` WHERE `id` = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $stmt->bind_result($id);
        $mysqli->close();
        return $id == null ? false : true;
    }

    /**
     * Save file in local storage and change file name in avatar
     * @param $file
     * @return void
     */
    public function changeAvatar($file)
    {

    }
}