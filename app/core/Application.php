<?php

namespace app\core;

use app\core\exception\ApplicationException;
use app\core\database\Config;
use app\core\Router;
use app\core\utils\PathManager;
use app\core\utils\WebsiteSettings;
use PDO;
use app\core\utils\menu\Menu;

class Application
{
    /**
     * PDO object for connection to database
     */
    private static PDO $PDO;

    /**
     * Main router
     */
    private static Router $router;

    /**
     * Page metadata
     */
    private static WebsiteSettings $website_settings;

    /**
     * Path manager
     */
    private static PathManager $path_manager;

    /**
     * Website menus
     * @var Menu[]
     */
    private static array $menus = [];

    /**
     * Get PDO object
     * @return PDO
     */
    public static function getPDO(): PDO
    {
        return self::$PDO;
    }

    /**
     * Create a tables in database
     * @param array $tables
     * @param Config $config
     * @return void
     */
    public static function initDatabase(Config $config, array $tables): void
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
     * Return website menu
     * @param string $id Menu id
     * @return Menu
     * @throws ApplicationException Throws if menu is not found
     */
    public static function getMenu(string $id): Menu
    {
        if(isset(self::$menus[$id]))
            return self::$menus[$id];
        else
            throw new ApplicationException("Menu is not found");
    }

    /**
     * Add new menu. **Menu will be replaced if the id is the same**
     * @param string $id Menu identifier
     * @param Menu $menu New menu object
     * @return void
     */
    public static function addMenu(string $id, Menu $menu): void
    {
        self::$menus[$id] = $menu;
    }

    /**
     * Remove menu
     * @param string $id Menu identifier
     * @return void
     * @throws ApplicationException Throws if menu is not found
     */
    public static function removeMenu(string $id): void
    {
        if(isset(self::$menus[$id]))
            unset(self::$menus[$id]);
        else
            throw new ApplicationException("Menu is not found");
    }

    /**
     * Set website settings
     * @param WebsiteSettings $metadata
     * @return void
     */
    public static function setWebsiteSettings(WebsiteSettings $metadata): void
    {
        self::$website_settings = $metadata;
    }

    /**
     * Get website settings
     * @return WebsiteSettings|null
     */
    public static function getWebsiteSettings(): ?WebsiteSettings
    {
        return self::$website_settings ?? null;
    }

    /**
     * Set path manager
     * @param PathManager $manager
     * @return void
     */
    public static function setPathManager(PathManager $manager): void
    {
        self::$path_manager = $manager;
    }

    /**
     * Get path manager
     * @return PathManager|null
     */
    public static function getPathManager(): ?PathManager
    {
        return self::$path_manager ?? null;
    }
}