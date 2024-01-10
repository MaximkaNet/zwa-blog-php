<?php

namespace app\controllers\api;

use app\core\exception\ApplicationException;
use app\core\http\Response;

class AuthAPIController {
    /**
     * Login api
     * @return string|null
     */
    public static function login(): ?string
    {
        $response_body = new Response();
        header("Content-Type: application/json");
        if (isset($_SESSION["user"])) {
            $response_body->setErrors([
                [
                    "message" => "User already logged in"
                ]
            ]);
            http_response_code(400);
            echo $response_body->toJSON();
            return null;
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
            echo $response_body->toJSON();
            return null;
        }

//        $service = new UserService();
        $login = function ($email, $password){
            if($email === "mail@mail.com" and password_verify($password, password_hash("123", PASSWORD_DEFAULT))){
                return [
                    "email" => "mail@mail.com",
                    "id" => 1,
                    "role" => "user"
                ];
            }
            throw new ApplicationException("Bad request", 400);
        };
        // Process login
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);

        try {
//            $user = $service->login($email, $password);
            $user = $login($email, $password);
            $_SESSION["user"]["is_auth"] = true;
            // ...
            $_SESSION["user"]["email"] = $user["email"];
            $_SESSION["user"]["id"] = $user["id"];
            $_SESSION["user"]["role"] = $user["role"];
            // ...
//            $_SESSION["user"]["email"] = $user->getEmail();
//            $_SESSION["user"]["id"] = $user->getId();
//            $_SESSION["user"]["role"] = $user->getRole();
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
        echo $response_body->toJSON();
        return null;
    }

    /**
     * Signup api
     * @return string|null
     */
    public static function signup(): ?string
    {
        $response_body = new Response();
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
        $response = new Response();
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
        echo $response->toJSON();
        return null;
    }
}