<?php

namespace app\core;

use app\core\router\Router;

class Application {
    private static Router $router;
    private static string $page_name;
    private static string $website_name;
    private static string $home_dir = "/";

    /**
     * Return the router object
     * @return Router
     */
    public static function getRouter(): Router
    {
        return Application::$router;
    }

    /**
     * Set router
     * @param Router $router
     */
    public static function setRouter(Router $router): void
    {
        Application::$router = $router;
    }

    /**
     * Return website name
     * @return string
     */
    public static function getWebsiteName(): string
    {
        return Application::$website_name;
    }

    /**
     * Set website name
     * @param string $website_name
     */
    public static function setWebsiteName(string $website_name): void
    {
        Application::$website_name = $website_name;
    }

    /**
     * Return page name
     * @return string
     */
    public static function getPageName(): string
    {
        return Application::$page_name;
    }

    /**
     * Set page name
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

    /**
     * Return home directory
     * @return mixed
     */
    public static function getHomeDir(): string
    {
        return self::$home_dir;
    }

    /**
     * Set home directory
     * @param mixed $home_dir
     */
    public static function setHomeDir(string $home_dir): void
    {
        self::$home_dir = $home_dir;
    }

    /**
     * Generate a correct link
     * @param string $url
     * @return string
     */
    public static function linkFor(string $url): string
    {
        $path_with_prefix = self::$home_dir;
        $path_with_prefix .= $url;

        $parts = explode("/", $path_with_prefix);
        $normalized = implode('/', array_filter($parts, function ($part) {
            return $part != '';
        }));
        return '/' . $normalized;
    }
}