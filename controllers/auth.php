<?php

namespace app\controllers;

class AuthController {
    public static function login(){
        include_once "views\auth\login.php";
    }
    public static function signup(){}
    public static function logout(){}
}