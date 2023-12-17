<?php

namespace app\controllers;

require_once "../core/http/response.php";
use app\core\http\ResponseBody;

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
            http_response_code(200);
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


        // Process login
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);

        function getUser(string $email): ?array
        {
            if($email == "mail@mail.com")
                return [
                    "email" => "mail@mail.com",
                    "password" => password_hash("123", PASSWORD_DEFAULT)
                ];
            return null;
        }

        $user = getUser($email);

        if(empty($user)) {
            $not_found = ["message" => "User not found"];
            $response_body->setErrors([$not_found]);
            http_response_code(404);
        }
        elseif (!password_verify($password, $user["password"])){
            $password_not_found = ["message" => "Incorrect password"];
            $data = [
                "user" => [
                    "email" => $email
                ]
            ];
            $response_body->setData($data);
            $response_body->setErrors([$password_not_found]);
            http_response_code(400);
        }
        else {
            $_SESSION["user"]["is_auth"] = true;
            $_SESSION["user"]["email"] = "mail@mail.com";
            http_response_code(200);
            $response_body->setMessage("Login success");
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

        $first_name = htmlspecialchars($_POST["first_name"]);
        $last_name = isset($_POST["last_name"]) ? htmlspecialchars($_POST["last_name"]) : null;
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);

        // Check email
        function getUser(string $email): ?array
        {
            if($email == "mail@mail.com")
                return [
                    "email" => "mail@mail.com",
                ];
            return null;
        }

        // Process registration
        $user = getUser($email);
        if(!empty($user)) {
            $response_body->setErrors([
                ["message" => "User already exists"]
            ]);
            http_response_code(409);
        }
        else {
            // Create the user ...
            http_response_code(200);
            $response_body->setMessage("User is created");
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
            http_response_code(200);
        }
        return $response->toJSON();
    }
}