<?php

namespace app\controllers\api;

use app\core\http\Response;
use app\core\http\ServerRequest;
use app\core\Router;
use domain\users\UserException;
use domain\users\UserService;

class UsersAPIController
{
    public static function edit(int $id): void
    {
        header("Content-Type: application/json");
        $request = new ServerRequest();
        $response_body = new Response();
        $avatar = $request->getUploadedFiles()["avatar"] ?? null;
        $data = [];
        $errors = [];
        if ($avatar->getError() === UPLOAD_ERR_OK) {
            $service = new UserService();
            try {
                $type = $avatar->getType();
                if(!match ($type) {
                    "png", "jpg", "jpeg", "ico", "svg" => true,
                    default=>false
                }){
                    throw new UserException("Bad file type");
                }
                $user = $service->changeAvatar($id, $avatar);
                $data["avatar"] = htmlspecialchars($user->getAvatar());
            } catch (UserException $e) {
                $errors[] = ["message" => $e->getMessage()];
            }
        }
        if (!empty($errors)) {
            $response_body->setErrors($errors);
            echo $response_body->toJSON();
            return;
        }
        try {
            $service = new UserService();
            if (isset($post["first_name"])) {
                if (strlen($post["first_name"]) > 10) {
                    throw new UserException("First name will be less then 20 chars");
                }
                if (empty($post["first_name"])) {
                    throw new UserException("First name not be empty");
                }
                $user = $service->getOne($id);
                $user->setFirstName($post["first_name"]);
                $service->editProfile($id, $user->getFullName());
                $data["first_name"] = htmlspecialchars($user->getFirstName());
            }
            if (isset($post["last_name"])) {
                if (strlen($post["last_name"]) > 10) {
                    throw new UserException("First name will be less then 10 chars");
                }
                $user = $service->getOne($id);
                $user->setLastName($post["last_name"]);
                $service->editProfile($id, $user->getFullName());
                $data["last_name"] = htmlspecialchars($user->getLastName());
            }
            $response_body->setMessage("Profile updated");
            $response_body->setData($data);
        } catch (UserException $e) {
            $response_body->setErrors([
                ["message" => $e->getMessage()]
            ]);
        }
        echo $response_body->toJSON();
    }

    public static function changeAvatar(int $id): void
    {
    }

    public static function delete(int $id): void
    {
    }
}