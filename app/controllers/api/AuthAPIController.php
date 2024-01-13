<?php

namespace app\controllers\api;

use app\core\exception\ApplicationException;
use app\core\http\Response;
use app\core\http\ServerRequest;
use app\helpers\validator\Validator;
use domain\users\UserException;
use domain\users\UserService;

class AuthAPIController
{
    /**
     * Login api
     * @return void
     */
    public static function login(): void
    {
        header("Content-Type: application/json");
        $response_body = new Response();
        try {
            // Main logic
            if (isset($_SESSION["user"])) {
                throw new ApplicationException("User already logged in", 400);
            }
            $request_body = (new ServerRequest())->getParsedBody();

            // Validate input data
            $email_validation_result = Validator::email($request_body["email"] ?? null, true);
            if ($email_validation_result->isNotValid()) {
                throw new ApplicationException($email_validation_result->getMessage());
            }
            if (empty($request_body["password"])) {
                throw new ApplicationException("Password is required");
            }

            $service = new UserService();
            // Process login
            $email = $request_body["email"];
            $password = $request_body["password"];
            $user = $service->login($email, $password);
            // Set session values
            $_SESSION["user"]["is_auth"] = true;
            $_SESSION["user"]["email"] = $user->getEmail();
            $_SESSION["user"]["id"] = $user->getId();
            $_SESSION["user"]["role"] = $user->getRole();
            // Success
            $response_body->setMessage("Login success");
            $response_body->setResponseCode(200);
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

    /**
     * Signup api
     * @return void
     */
    public static function signup(): void
    {
        $response_body = new Response();
        header("Content-Type: application/json");
        try {
            $request_body = (new ServerRequest())->getParsedBody();
            $validators = [
                "first_name" => Validator::firstName($request_body["first_name"] ?? null, true),
                "last_name" => Validator::lastName($request_body["last_name"] ?? null),
                "email" => Validator::email($request_body["email"] ?? null, true),
                "password" => Validator::password($request_body["password"] ?? null, true),
                "confirm_password" => Validator::passwordConfirm($request_body["confirm_password"] ?? null, true)
            ];

            foreach ($validators as $validator) {
                if ($validator->isNotValid()) {
                    throw new ApplicationException($validator->getMessage(), 400);
                }
            }

            // Check passwords
            if ($request_body["password"] == $request_body["confirm_password"]) {
                $service = new UserService();
                $user = $service->registration(
                    $request_body["email"],
                    $request_body["password"],
                    $request_body["first_name"],
                    $request_body["last_name"] ?? null
                );
                $response_body->setResponseCode(201);
                $response_body->setMessage("User is created");
            } else {
                throw new ApplicationException("Passwords do not match", 400);
            }
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

    /**
     * Logout api
     * @return void
     */
    public static function logout(): void
    {
        $response = new Response();
        header("Content-Type: application/json");
        try {
            if (isset($_SESSION["user"])) {
                $_SESSION["user"] = null;
                $response->setMessage("User logout successful");
                $response->setResponseCode(200);
            } else {
                throw new ApplicationException("User already logged out", 400);
            }
        } catch (ApplicationException $e) {
            $response->setResponseCode($e->getCode());
            $response->addError($e->getMessage());
        } catch (\Exception $e) {
            $code = $e->getCode();
            $response->addError($e->getMessage(), is_numeric($code) ? $code : null);
        }
        http_response_code($response->getResponseCode());
        echo $response->toJSON();
    }
}