<?php

namespace domain\users;

use app\core\database\MysqlConfig;
use app\core\exception\RequestException;
use app\core\http\UploadedFile;
use app\core\Application;
use app\core\Router;

/**
 * The service for User entity
 */
class UserService
{
    /**
     * Register a new user
     * @param string $email
     * @param string $password
     * @param string $first_name
     * @param string|null $last_name
     * @return User Registered user
     * @throws UserException
     */
    public function registration(
        string $email,
        string $password,
        string $first_name,
        string $last_name = null
    ): User
    {
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $repo = UsersRepository::init($config->getPDO());
        // Check user in database
        $user_exists = $repo->findOne(["email" => $email]);
        // if exists throws an exception
        if($user_exists) throw UserException::Conflict("User already exists");
        // Create a new record in database
        $created_user_id = $repo->create([
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "full_name" => $first_name . (isset($last_name) ? " " . $last_name : ""),
            "role" => "user"
        ]);
        // Returns registered user with id
        return $repo->findById($created_user_id);
    }

    /**
     * Login exists user
     * @param string $email
     * @param string $password
     * @return User
     * @throws UserException Throws if <b>user in not found</b> or <b>incorrect password</b>
     */
    public function login(string $email, string $password): User
    {
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $repo = UsersRepository::init($config->getPDO());
        // Get user from database
        $user = $repo->findOne(["email" => $email]);
        // If not exist throw new exception
        if(!$user) throw UserException::NotFound("User not found");
        // Verify password
        if(password_verify($password, $user->getPassword()))
        {
            return $user;
        }
        else
            throw UserException::BadRequest("Incorrect password");
    }

    /**
     * Change user password
     * @param int $id User id
     * @param string $new_password New password in plain text
     * @return User
     * @throws UserException Throws if not found
     */
    public function changePassword(int $id, string $new_password): User
    {
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $repo = UsersRepository::init($config->getPDO());
        // Get user from database
        $user = $repo->findById($id);
        // If not exist throw new exception
        if(!$user) throw UserException::NotFound("User not found");
        // Hash a new password
        $user->setPassword($new_password, true);
        // Update user
        $repo->update(["password" => $user->getPassword()], ["id" => $id]);
        return $user;
    }

    /**
     * Change user avatar
     * @param int $id User id
     * @param UploadedFile $avatar The uploaded avatar
     * @return User
     * @throws UserException
     */
    public function changeAvatar(int $id, UploadedFile $avatar): User
    {
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $repo = UsersRepository::init($config->getPDO());
        // Get user from database
        $user = $repo->findById($id);
        // If not exists throw exception
        if(!$user) throw UserException::NotFound("User not found");
        // Save uploaded file to storage
        try {

            $prev_avatar = __DIR__ . "/../../static/users/" . $user->getAvatar();
            if(file_exists($prev_avatar)){
                unlink($prev_avatar);
            }
            $uploads_path = Router::link("/static/users", $_ENV["URL_PREFIX"]);
            if(!$avatar->moveTo($uploads_path)) {
                throw new UserException("File not uploaded");
            }
            // Set user avatar
            $user->setAvatar($avatar->getName() .".". $avatar->getType());
            // Update user avatar in database
            $repo->update(["avatar" => $user->getAvatar()], ["id" => $id]);
            return $user;
        } catch (RequestException $e){
            throw new UserException($e->getMessage(), $e->getCode());
        } catch (UserException $e) {
            throw $e;
        }
    }

    /**
     * Change user role
     * @param int $id
     * @param string $role
     * @param int $initiator_id
     * @return User
     * @throws UserException
     */
    public function changeRole(int $id, string $role, int $initiator_id): User
    {
        // Throws exception if initiator want change own role
        if($id === $initiator_id) throw UserException::Forbidden();
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $repo = UsersRepository::init($config->getPDO());
        // Get user from database
        $user = $repo->findById($id);
        $initiator = $repo->findById($initiator_id);
        // If not found, throws new not found exception
        if(!$user) throw UserException::NotFound("User not found");
        else if(!$initiator) throw UserException::NotFound("Initiator not found");
        // Check permissions
        if($initiator->canPromoteTo($role)) $user->setRole($role);
        else throw UserException::Forbidden();
        // Update user
        $repo->update(["role" => $user->getRole()], ["id" => $id]);
        return $user;
    }

    /**
     * Edit user profile
     * @param int $id user id
     * @param string $full_name
     * @return User
     * @throws UserException
     */
    public function editProfile(int $id, string $full_name): User
    {
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $repo = UsersRepository::init($config->getPDO());
        // Get user from database
        $user = $repo->findById($id);
        // If not found, throws not found exception
        if(!$user) throw UserException::NotFound("User not found");
        // Update user information
        $user->setFullName($full_name);
        $repo->update(["full_name" => $full_name], ["id" => $id]);
        return $user;
    }

    /**
     * Get the number of users
     * @return int
     */
    public function count(): int
    {
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $repo = UsersRepository::init($config->getPDO());
        return $repo->count();
    }

    /**
     * Get part users
     * @param int $page
     * @param int $count
     * @return array<User>
     * @throws UserException Throws UserException if users not found
     */
    public function getPart(int $page = 0, int $count = 10): array
    {
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $repo = UsersRepository::init($config->getPDO());
        // Get part of users using $page and $count limiter
        $users = $repo->findAll(null, ["limit" => [$count, $page * $count]]);
        // If users not found throw not found exception
        if(!$users) throw UserException::NotFound("Users not found");
        // Return users
        return $users;
    }

    /**
     * Get one user
     * @param int $id
     * @return User
     * @throws UserException
     */
    public function getOne(int $id): User
    {
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $repo = UsersRepository::init($config->getPDO());
        // Get user by id
        $user = $repo->findById($id);
        // If user is not found throw not found exception
        if(!$user) throw UserException::NotFound("User not found");
        // Return user
        return $user;
    }

    /**
     * Delete user by id
     * @param int $id
     * @return User Returns deleted user
     * @throws UserException Throws if user is not found
     */
    public function delete(int $id): User
    {
        $config = new MysqlConfig($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $repo = UsersRepository::init($config->getPDO());
        $user = $repo->findById($id);
        // If user is not found throw not found exception
        if(!$user) throw UserException::NotFound("User not found");
        $repo->delete(["id" => $id]);
        return $user;
    }

    /**
     * Search user by email or first_name or last_name
     * @param string $needle
     * @param int $page
     * @param int $count
     * @return array|null
     * @throws UserException
     */
    public function search(string $needle, int $page = 0, int $count = 10): array|null
    {
        throw new UserException("Service unavailable");
//        $repo = new UsersRepository(Application::getPDO());
//        $users = $repo->findAll([
//            "email" => $needle,
//            UsersRepository::OP_OR,
//            "first_name" => $needle,
//            UsersRepository::OP_OR,
//            "last_name" => $needle,
//        ], ["limit" => [$count, $page * $count]]);
//        if(!$users) throw UserException::NotFound("users");
//        return $users;
    }
}