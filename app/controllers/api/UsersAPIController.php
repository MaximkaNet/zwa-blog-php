<?php

namespace app\controllers\api;

use app\core\exception\ApplicationException;
use app\core\http\Response;
use app\core\http\ServerRequest;
use app\core\http\UploadedFile;
use app\helpers\validator\Validator;
use domain\users\UserService;

class UsersAPIController
{
    public static function edit(int $id): void
    {
        header("Content-Type: application/json");
        $response_body = new Response();
        try {
            $request = new ServerRequest();
            $request_body = $request->getParsedBody();
            // Check files file
//            $file_upload_length = $request->getServerParams()["CONTENT_LENGTH"];
//            if ($file_upload_length > 1000) {
//                throw new ApplicationException("Big file size");
//            }
            $changed = false;
            $service = new UserService();
            $user = $service->getOne($id);
            // Handle fields
            if (isset($request_body["first_name"])) {
                $validation_res = Validator::firstName($request_body["first_name"], true);
                if ($validation_res->isNotValid()) {
                    throw new ApplicationException($validation_res->getMessage(), 400);
                }
                $user->setFirstName($request_body["first_name"]);
                $changed = true;
            }
            if (isset($request_body["last_name"])) {
                $validation_res = Validator::lastName($request_body["last_name"], true);
                if ($validation_res->isNotValid()) {
                    throw new ApplicationException($validation_res->getMessage(), 400);
                }
                $user->setLastName($request_body["last_name"]);
                $changed = true;
            }
            if ($changed) {
                $service->editFullName($user->getId(), $user->getFullName());
                $response_body->addData([
                    "first_name" => $user->getFirstName(),
                    "last_name" => $user->getLastName(),
                ]);
            }
            // Handle avatar
            /** @var UploadedFile $avatar */
            $avatar = $request->getUploadedFiles()["avatar"] ?? null;
            if (isset($avatar) and $avatar->getError() !== UPLOAD_ERR_NO_FILE) {
                $service->changeAvatar($user->getId(), $avatar, ["png", "jpg", "jpeg", "ico", "svg"]);
                $response_body->addData(["avatar" => $avatar->getName()]);
                $changed = true;
            }
            $response_body->setResponseCode(200);
            $response_body->setMessage($changed ? "Changes saved" : "Data is not changed");
        } catch (ApplicationException $e) {
            $response_body->setResponseCode($e->getCode());
            $response_body->addError($e->getMessage());
        } catch (\Exception $e) {
            $code = $e->getCode();
            $response_body->addError($e->getMessage(), is_numeric($code) ? $code : null);
        }
        http_response_code($response_body->getResponseCode());
        echo $response_body->toJSON();
    }

    public static function delete(int $id): void
    {
    }
}