<?php

namespace app\controllers;

require_once $_SERVER["DOCUMENT_ROOT"] . "/core/http/response.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/domain/users/userService.php";

use app\core\Application;
use app\core\exception\ApplicationException;
use app\core\http\ResponseBody;
use app\domain\users\UserService;

class AuthAPIController {
    /**
     * Login api
     * @return string|null
     */
    public static function login(): ?string
    {
        $response_body = new ResponseBody();
        header("Content-Type: application/json");
        if (isset($_SESSION["user"])) {
            $response_body->setErrors([
                [
                    "message" => "User already logged in"
                ]
            ]);
            http_response_code(400);
            return $response_body->toJSON();
        }

        // Check input values
        $validation_errors = [];
        if(empty($_POST["email"])) {
            $validation_errors[] = ["message" => "Email field is empty"];
        }
        if (empty($_POST["password"])) {
            $validation_errors[] = ["message" => "Password field is empty"];
        }

        if(!empty($validation_errors)){
            $response_body->setErrors($validation_errors);
            http_response_code(400);
            return $response_body->toJSON();
        }

        $service = new UserService();
        // Process login
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);

        try {
            $user = $service->login($email, $password);
            $_SESSION["user"]["is_auth"] = true;
            $_SESSION["user"]["email"] = $user->getEmail();
            $_SESSION["user"]["id"] = $user->getId();
            $_SESSION["user"]["role"] = $user->getRole();
            http_response_code(200);
            $response_body->setMessage("Login success");
        } catch (ApplicationException $exception) {
            $response_body->setErrors([["message" => $exception->getMessage()]]);
            $response_body->setData([
                "user" => [
                    "email" => $email
                ]
            ]);
            http_response_code($exception->getCode());
        }
        return $response_body->toJSON();
    }

    /**
     * Signup api
     * @return string|null
     */
    public static function signup(): ?string
    {
        $response_body = new ResponseBody();
        header("Content-Type: application/json");

        // Validation input fields
        $validation_errors = [];
        if(empty($_POST["first_name"])){
            $validation_errors[] = ["message" => "First name is required"];
        }
        if(empty($_POST["email"])){
            $validation_errors[] = ["message" => "Email is required"];
        }
        if(empty($_POST["password"])){
            $validation_errors[] = ["message" => "Password is required"];
        }

        if(!empty($validation_errors)){
            $response_body->setErrors($validation_errors);
            http_response_code(400);
            return $response_body->toJSON();
        }

        $service = new UserService();
        $first_name = htmlspecialchars($_POST["first_name"]);
        $last_name = isset($_POST["last_name"]) ? htmlspecialchars($_POST["last_name"]) : null;
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);

        // Process registration
        try {
            $user = $service->registration($email, $password, $first_name, $last_name);
            http_response_code(201);
            $response_body->setMessage("User is created");
        } catch (ApplicationException $exception) {
            $response_body->setErrors([
                ["message" => "User already exists"]
            ]);
            http_response_code(409);
        }
        return $response_body->toJSON();
    }

    /**
     * Logout api
     * @return string|null
     */
    public static function logout(): ?string
    {
        $response = new ResponseBody();
        header("Content-Type: application/json");
        if(isset($_SESSION["user"])){
            session_unset();
            $response->setMessage("User logout successful");
            http_response_code(200);
        }
        else {
            $response->setErrors([["message" => "User already logged out"]]);
            http_response_code(400);
        }
        return $response->toJSON();
    }
}