<?php
namespace app\core;
class Router {
    private array $routes = [];
    private string $prefix;

    /**
     * Prefix in url
     * @example http://domain.com/prefix/single_page - single page
     * @example http://domain.com/prefix - home page
     * @param string $prefix
     */
    function __construct(string $prefix = "") {
        $this->prefix = $prefix;
        $this->setRoute('/404', 'get', function () {
            return [
                "template" => "404",
                "page_title" => "Page not found"
            ];
        });
    }

    /**
     * Return base
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Set request method GET
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function get(string $path, callable $callback): void
    {
        $this->setRoute($path,"get", $callback);
    }

    /**
     * Set request method POST
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function post(string $path, callable $callback): void
    {
        $this->setRoute($path,"post", $callback);
    }

    /**
     * Set request method PUT
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function put(string $path, callable $callback): void
    {
        $this->setRoute($path,"put", $callback);
    }

    /**
     * Set request method PATCH
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function patch(string $path, callable $callback): void
    {
        $this->setRoute($path,"patch", $callback);
    }

    /**
     * Set request method DELETE
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function delete(string $path, callable $callback): void
    {
        $this->setRoute($path,"delete", $callback);
    }

    /**
     * Set not found callback
     * @param callable $callback
     * @return void
     */
    public function notFound(callable $callback): void
    {
        $this->setRoute('/404', 'get', $callback);
    }

    /**
     * Append the route
     * @param string $path
     * @param string $method
     * @param callable $callback
     * @return void
     */
    public function setRoute(string $path, string $method, callable $callback): void
    {
        $methods = explode("|", strtoupper($method));
        foreach ($methods as $_method){
            $normalized_route = Router::normalizePath($path, $this->prefix);
            // Get params
            $regexp_route = $normalized_route;
            // Replace optional
            $regexp_route = preg_replace("/\/:([^\/]+)\?/", "/?([^\/]+)?", $regexp_route);
            // Replace regular
            $regexp_route = preg_replace("/\/:([^\/]+)/", "/([^\/]+)", $regexp_route);
            $this->routes[$_method][$normalized_route] = [
                "callback" => $callback,
                "regexp" => $regexp_route
            ];
        }
    }

    /**
     * Get the route
     * @param string $path
     * @param string $method
     * @return mixed|null
     */
    public function getRoute(string $path, string $method): mixed
    {
        return $this->routes[strtoupper($method)][Router::normalizePath($path, $this->prefix)];
    }

    /**
     * Return normalized path
     * @param string $path
     * @param string $prefix
     * @return string
     */
    private static function normalizePath(string $path, string $prefix = ""): string
    {
        $path_with_prefix = $prefix;
        $path_with_prefix .= $path[0] != "/" ? "/" : "";
        $path_with_prefix .= $path;

        $parts = explode("/", $path_with_prefix);
        $normalized = implode('/', array_filter($parts, function ($part) {
            return $part != '';
        }));
        return $parts[0] == ".." || $parts[0] == "." ? $normalized : ("/" . $normalized);
    }

    /**
     * Create link
     * @param string $path
     * @param string $prefix
     * @return string
     */
    public static function link(string $path, string $prefix = ""): string
    {
        return Router::normalizePath($path, $prefix);
    }

    /**
     * Match url with route.
     * @param string $url
     * @param string $route
     * @param array $params
     * @return false|int
     */
    public static function matchUrl(string $url, string $route, &$params): int|false
    {
        // Check if the route matches the url
        $match_result = preg_match("#^" . $route ."$#", $url, $params);
        array_shift($params);
        return $match_result;
    }

    /**
     * Match request url with routes and call callback
     * @param string $path
     * @param string $method
     * @return View
     */
    public function resolve (string $path, string $method): View
    {
        if (isset($this->routes[$method]))
            foreach ($this->routes[$method] as $route) {
                if (Router::matchUrl($path, $route["regexp"], $params)) {
                    $props = call_user_func($route["callback"], ...$params);
                    $view = new View($props["template"]);
                    $view->setContext($props["context"] ?? []);
                    return $view;
                }
            }
        // Not found page
        $props = call_user_func($this->getRoute('/404', 'get')["callback"]);
        $view = new View("404");
        $view->setContext($props["context"] ?? []);
        return $view;
    }
}