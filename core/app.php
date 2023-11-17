<?php

namespace app\core;

use app\core\router\Router;

class Application {
    private static $router;
    private static $page_name;
    private static $website_name;
    private static $database;

    /**
     * @return Router
     */
    public static function getRouter(): Router
    {
        return Application::$router;
    }

    /**
     * @param Router $router
     */
    public static function setRouter(Router $router): void
    {
        Application::$router = $router;
    }

    /**
     * @return string
     */
    public static function getWebsiteName(): string
    {
        return Application::$website_name;
    }

    /**
     * @param string $website_name
     */
    public static function setWebsiteName(string $website_name): void
    {
        Application::$website_name = $website_name;
    }

    /**
     * @return string
     */
    public static function getPageName(): string
    {
        return Application::$page_name;
    }

    /**
     * @param string $page_name
     */
    public static function setPageName(string $page_name): void
    {
        Application::$page_name = $page_name;
    }

    public static function getPageTitle(string $separator = " - ", bool $with_website_name = true): string
    {
        return Application::getPageName() . ($with_website_name ? $separator . Application::getWebsiteName() : "");
    }

    public static function getDatabase()
    {
        return Application::$database;
    }

    public static function setDatabase($database): void
    {
        Application::$database = $database;
    }
}