<?php

namespace app\controllers\api;

use app\core\http\Response;
use domain\users\UserException;
use domain\users\UserService;

class UsersAPIController
{
    public static function edit(int $id): void
    {
        $response_body = new Response();
        $input = file_get_contents('php://input');
        $post = json_decode($input, true);
        header("Content-Type: application/json");
        if (json_last_error() !== JSON_ERROR_NONE) {
            $response_body->setErrors([
                ["message" => "Incorrect request", "data" => "$input"]
            ]);
            echo $response_body->toJSON();
            return;
        }
        try {
            $service = new UserService();
            $data = [];
            if (isset($post["first_name"])) {
                if(strlen($post["first_name"]) > 10){
                    throw new UserException("First name will be less then 20 chars");
                }
                if(empty($post["first_name"])) {
                    throw new UserException("First name not be empty");
                }
                $user = $service->getOne($id);
                $user->setFirstName($post["first_name"]);
                $service->editProfile($id, $user->getFullName());
                $data["first_name"] = htmlspecialchars($user->getFirstName());
            }
            if (isset($post["last_name"])) {
                if(strlen($post["last_name"]) > 10){
                    throw new UserException("First name will be less then 10 chars");
                }
                $user = $service->getOne($id);
                $user->setLastName($post["last_name"]);
                $service->editProfile($id, $user->getFullName());
                $data["last_name"] = htmlspecialchars($user->getLastName());
            }
            $response_body->setMessage("Profile updated");
            $response_body->setData($data);
            echo $response_body->toJSON();
        } catch (UserException $e) {
            $response_body->setErrors([
                ["message" => $e->getMessage()]
            ]);
            echo $response_body->toJSON();
        }
    }

    public static function changeAvatar(int $id): void
    {
    }

    public static function delete(int $id): void
    {
    }
}