<?php

namespace app\controllers;

use app\core\exception\ApplicationException;
use app\core\Router;

class AuthController
{
    /**
     * Render view
     * @return array
     * @throws ApplicationException
     */
    public static function login(): array
    {
        try {
            $context = [
                "head" => [
                    "title" => "Login"
                ],
                "auth" => [
                    "class_list" => "bg-primary",
                    "scripts" => [
                        "auth" => Router::link("/assets/js/auth.js", $_ENV["URL_PREFIX"])
                    ]
                ],
            ];
            if(isset($_SESSION["user"]["is_auth"]) and $_SESSION["user"]["is_auth"]){
                $context["auth"]["status"] = [
                    "user" => [
                        "email" => $_SESSION["user"]["email"]
                    ],
                    "logo" => [
                        "link" => Router::link("/assets/images/logo.svg", $_ENV["URL_PREFIX"])
                    ],
                    "continue" => [
                        "link" => Router::link("/", $_ENV["URL_PREFIX"])
                    ]
                ];
            } else {
                $context["auth"]["login"] = [
                    "logo" => [
                        "link" => Router::link("/assets/images/logo.svg", $_ENV["URL_PREFIX"])
                    ],
                    "images" => [
                        "email" => Router::link("/assets/images/email.svg", $_ENV["URL_PREFIX"]),
                        "password" => Router::link("/assets/images/password.svg", $_ENV["URL_PREFIX"])
                    ],
                    "links" => [
                        "signup" => Router::link("/signup", $_ENV["URL_PREFIX"])
                    ]
                ];
            }
            return ["template" => "auth", "context" => $context];
        }catch (ApplicationException $e){
            throw $e;
        }
    }

    /**
     * Render view
     * @return array
     * @throws ApplicationException
     */
    public static function signup(): array
    {
        try {
            $context = [
                "head" => [
                    "title" => "Signup"
                ],
                "auth" => [
                    "class_list" => "bg-gradient full-size",
                    "scripts" => [
                        "auth" => Router::link("/assets/js/auth.js", $_ENV["URL_PREFIX"])
                    ]
                ],
            ];
            $context["auth"]["signup"] = [
                "logo" => [
                    "link" => Router::link("/assets/images/logo.svg", $_ENV["URL_PREFIX"])
                ],
                "images" => [
                    "email" => Router::link("/assets/images/email.svg", $_ENV["URL_PREFIX"]),
                    "password" => Router::link("/assets/images/password.svg", $_ENV["URL_PREFIX"])
                ],
                "links" => [
                    "login" => Router::link("/login", $_ENV["URL_PREFIX"])
                ]
            ];
            return ["template" => "auth", "context" => $context];
        } catch (ApplicationException $e){
            throw $e;
        }
    }

    /**
     * Render view
     * @return array
     * @throws ApplicationException
     */
    public static function logout(): array
    {
        try {
            if(empty($_SESSION["user"])) {
                $to_login = Router::link("/login", $_ENV["URL_PREFIX"]);
                header("Location: $to_login", true, 301);
                return [];
            }
            $context = [
                "head" => [
                    "page_title" => "Logout"
                ],
                "auth" => [
                    "class_list" => "bg-primary",
                    "scripts" => [
                        "auth" => Router::link("/assets/js/auth.js", $_ENV["URL_PREFIX"])
                    ]
                ]
            ];
            $context["auth"]["logout"] = [
                "logo" => [
                    "link" => Router::link("/assets/images/logo.svg", $_ENV["URL_PREFIX"])
                ],
                "links" => [
                    "home" =>  Router::link("/category/articles", $_ENV["URL_PREFIX"])
                ]
            ];
            return ["template" => "auth", "context" => $context];
        } catch (ApplicationException $e) {
            throw $e;
        }
    }
}