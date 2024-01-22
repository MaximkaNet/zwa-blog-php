<?php

namespace app\controllers\api;

use app\core\exception\ApplicationException;
use app\core\http\Response;
use app\core\http\ServerRequest;
use domain\categories\CategoriesService;
use domain\posts\PostsService;
use domain\users\UserRole;

class PostsAPIController
{
    public static function create(): void
    {
        // Check user authorized and role
        header("Content-Type: application/json");
        $response = new Response();
        try {
            if (empty($_SESSION["user"])) {
                throw new ApplicationException("User is not authorized", 401);
            } elseif ($_SESSION["user"]["role"] !== UserRole::ADMIN) {
                throw new ApplicationException("Access denied", 403);
            }
            $request = (new ServerRequest())->getParsedBody();
            $posts_service = PostsService::get();
            $category_service = CategoriesService::get();
            $data = [
                "title" => $request["title"] ?? "The title",
                "content" => $request["content"] ?? "",
                "user_id" => $_SESSION["user"]["id"],
                "category_id" => $request["category_id"] ?? $category_service->getByName("")
            ];
            $posts_service->create(...$data);
            $response->setResponseCode(201);
            $response->setMessage("Post was created");
        } catch (ApplicationException $e) {
            $code = is_numeric($e->getCode()) ? $e->getCode() : null;
            $response->setResponseCode($code);
            $response->addError($e->getMessage());
        } catch (\Exception $e) {
            $code = is_numeric($e->getCode()) ? $e->getCode() : null;
            $response->setResponseCode($code);
            $response->addError($e->getMessage());
        }
        http_response_code($response->getResponseCode());
        echo $response->toJSON();
    }

    public static function edit(int $id): void
    {
        header("Content-Type: application/json");
        $response = new Response();
        try {
            if (empty($_SESSION["user"])) {
                throw new ApplicationException("User is not authorized", 401);
            } elseif ($_SESSION["user"]["role"] !== UserRole::ADMIN) {
                throw new ApplicationException("Access denied", 403);
            }
            $request = (new ServerRequest())->getParsedBody();
            $service = PostsService::get();
            $changed = false;
            // Validation
            if (isset($request["title"]) and !empty($request["title"])) {
                $service->editTitle($id, $request["title"]);
                $changed = true;
            }
            if (isset($request["content"]) and !empty($request["content"])) {
                $service->editContent($id, $request["content"]);
                $changed = true;
            }
            if (isset($request["category_id"]) and !empty($request["category_id"])) {
                $service->changeCategory($id, $request["category_id"]);
                $changed = true;
            }
            $response->setResponseCode(200);
            $response->setMessage($changed ? "Changes saved" : "Without changes");
        } catch (ApplicationException $e) {
            $code = is_numeric($e->getCode()) ? $e->getCode() : null;
            $response->setResponseCode($code);
            $response->addError($e->getMessage());
        } catch (\Exception $e) {
            $code = is_numeric($e->getCode()) ? $e->getCode() : null;
            $response->setResponseCode($code);
            $response->addError($e->getMessage());
        }
        http_response_code($response->getResponseCode());
        echo $response->toJSON();
    }

    public static function delete(int $id): void
    {
        header("Content-Type: application/json");
        $response = new Response();
        try {
            if (empty($_SESSION["user"])) {
                throw new ApplicationException("User is not authorized", 401);
            } elseif ($_SESSION["user"]["role"] !== UserRole::ADMIN) {
                throw new ApplicationException("Access denied", 403);
            }
            $service = PostsService::get();
            $service->delete($id);
            $response->setResponseCode(200);
            $response->setMessage("Post $id deleted");
        } catch (ApplicationException $e) {
            $code = is_string($e->getCode()) ? null : $e->getCode();
            $response->setResponseCode($code);
            $response->addError($e->getMessage());
        } catch (\Exception $exception) {
            $code = is_string($exception->getCode()) ? null : $exception->getCode();
            $response->setResponseCode($code);
            $response->addError($exception->getMessage());
        }
        http_response_code($response->getResponseCode());
        echo $response->toJSON();
    }
}