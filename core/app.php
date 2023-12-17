<?php

namespace app\core;

use app\core\router\Router;
use \PDO;

class Application {
    private static PDO $PDO;
    private static Router $router;
    private static string $page_name;
    private static string $website_name;
    private static string $home_dir = "/";

    /**
     * @param PDO $PDO
     * @deprecated
     */
    public static function setPDO(PDO $PDO): void
    {
        self::$PDO = $PDO;
    }

    /**
     * @return PDO
     */
    public static function getDatabaseConnection(): PDO
    {
        return self::$PDO;
    }

    /**
     * Create a tables in database
     * @param array $tables
     * @param DatabaseConfiguration $config
     * @return void
     */
    public static function initDatabase(DatabaseConfiguration $config, array $tables): void
    {
        self::$PDO = new PDO($config->toDsn("mysql"), $config->getUsername(), $config->getPassword());
        $query = implode(" ", $tables);
        $stmt = self::$PDO->prepare($query);
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * Return the router object
     * @return Router
     */
    public static function getRouter(): Router
    {
        return self::$router;
    }

    /**
     * Set router
     * @param Router $router
     */
    public static function setRouter(Router $router): void
    {
        self::$router = $router;
    }

    /**
     * Return website name
     * @return string
     */
    public static function getWebsiteName(): string
    {
        return self::$website_name;
    }

    /**
     * Set website name
     * @param string $website_name
     */
    public static function setWebsiteName(string $website_name): void
    {
        self::$website_name = $website_name;
    }

    /**
     * Return page name
     * @return string
     */
    public static function getPageName(): string
    {
        return self::$page_name;
    }

    /**
     * Set page name
     * @param string $page_name
     */
    public static function setPageName(string $page_name): void
    {
        self::$page_name = $page_name;
    }

    public static function getPageTitle(string $separator = " - ", bool $with_website_name = true): string
    {
        return self::getPageName() . ($with_website_name ? $separator . self::getWebsiteName() : "");
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