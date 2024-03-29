<?php

namespace app\core;

use app\core\exception\ApplicationException;
use app\core\http\ServerRequest;

class Application
{
    /**
     * Main router
     */
    private Router $router;

    private Meta $meta_head;

    public function __construct()
    {
        $this->meta_head = new Meta();
        $this->meta_head->setLanguage(Meta::LANG_CZ);
        $this->meta_head->setFaviconLink(Router::link("/assets/images/favicon.ico", $_ENV["URL_PREFIX"]));
    }

    public function createFolders(string $project_root, array|string $paths = ["/static", "/static/users"]): void
    {
        foreach ($paths as $path) {
            $path_to_folder = $project_root . $path;
            if (!file_exists($path_to_folder)){
                mkdir($path_to_folder);
            } elseif (file_exists($path_to_folder) and !is_dir($path_to_folder)) {
                mkdir($path_to_folder);
            }
        }
    }

    /**
     * Create router
     * @param string|null $prefix
     * @return Router
     */
    public function createRouter(string $prefix = null): Router
    {
        return new Router($prefix ?? "");
    }

    /**
     * Apply router to application
     * @param Router $router
     * @return void
     */
    public function applyRouter(Router $router): void
    {
        $this->router = $router;
    }

    /**
     * Run an application
     * @return void
     * @throws ApplicationException
     */
    public function run(): void
    {
        $req = new ServerRequest();
        $request_uri = $req->getServerParams()["REQUEST_URI"];
        $request_method = $req->getServerParams()["REQUEST_METHOD"];
        if (empty($this->router)) {
            throw new ApplicationException("Router has not applied");
        }
        session_start();
        $view = $this->router->resolve($request_uri, $request_method);
        if (is_string($view)) {
            echo $view;
        } elseif ($view instanceof View) {
            $view->addValuesToContext([
                "app" => [
                    "lang" => $this->meta_head->getLanguage()
                ],
                "head" => [
                    "favicon_link" => $this->meta_head->getFaviconLink(),
                    "stylesheets" => [
                        ["link" => Router::link("/assets/css/style.css", $_ENV["URL_PREFIX"])]
                    ]
                ]
            ]);
            echo $view->render();
        }
    }
}