<?php

namespace app\core\router;
class Router {
    private $routes = [];
    private $prefix;
    /**
     * Prefix in url
     * @example http://domain.com/prefix/single_page - single page
     * @example http://domain.com/prefix - home page
     * @param string $prefix
     */
    function __construct(string $prefix = "") {
        $this->prefix = $prefix;
        $this->setRoute('/404', 'get', function (){
            echo 'Page not found';
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
    public function get(string $path, callable $callback)
    {
        $this->setRoute($path,"get", $callback);
    }

    /**
     * Set request method POST
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function post(string $path, callable $callback)
    {
        $this->setRoute($path,"post", $callback);
    }

    /**
     * Set request method PUT
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function put(string $path, callable $callback)
    {
        $this->setRoute($path,"put", $callback);
    }

    /**
     * Set request method PATCH
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function patch(string $path, callable $callback)
    {
        $this->setRoute($path,"patch", $callback);
    }

    /**
     * Set request method DELETE
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function delete(string $path, callable $callback)
    {
        $this->setRoute($path,"delete", $callback);
    }

    /**
     * Set not found callback
     * @param callable $callback
     * @return void
     */
    public function notFound(callable $callback)
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
    public function setRoute(string $path, string $method, callable $callback)
    {
        $methods = explode("|", strtoupper($method));
        foreach ($methods as $_method){
            $this->routes[$_method][Router::normalizePath($path, $this->prefix)] = $callback;
        }
    }

    /**
     * Get the route
     * @param string $path
     * @param string $method
     * @return mixed
     */
    public function getRoute(string $path, string $method)
    {
        return $this->routes[strtoupper($method)][Router::normalizePath($path, $this->prefix)];
    }
    /**
     * Get request method
     * @return string
     */
    public static function getMethod(): string
    {
        return strtoupper($_SERVER["REQUEST_METHOD"]);
    }

    /**
     * Get uri without query string
     * @return string
     */
    public static function getPath(): string
    {
        $path = explode("?", $_SERVER["REQUEST_URI"])[0];
        return Router::normalizePath($path);
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
        return '/' . $normalized;
    }

    /**
     * Return relative link like this: ./relative_link
     * @param string $path
     * @return string
     */
    public static function relativeLink(string $path): string
    {
        return '.' . Router::normalizePath($path);
    }

    /**
     * Return absolute link like this: /absolute_link
     * @param string $path
     * @param string $prefix
     * @return string
     */
    public static function absoluteLink(string $path, string $prefix = ""): string
    {
        return Router::normalizePath($path, $prefix);
    }

    /**
     * Get query param
     * @param string $key
     * @return string
     */
    public static function getQueryParam(string $key): string
    {
        return $_GET[$key];
    }

    /**
     * Match url with route. If match is success, return match result
     * @param string $url
     * @param string $route
     * @param string[] $matches
     * @return false|int
     */
    public static function matchUrl(string $url, string $route, &$matches)
    {
        $selector_pattern = '/\/:([^\/]+)/'; // :example_param
        $param_pattern = "/([^/*]+)";
        $pattern = preg_replace($selector_pattern, $param_pattern, $route);
        // Select a suitable route
        $match_result = preg_match("#^" . $pattern ."$#", $url, $matches);
        array_shift($matches); // remove the first item
        return $match_result;
    }

    /**
     * Match request url with routes and call callback
     * @param string $path
     * @param string $method
     * @return mixed
     */
    public function resolve (string $path, string $method): bool
    {
        if (isset($this->routes[$method]))
            foreach ($this->routes[$method] as $route => $callback) {
                if (Router::matchUrl($path, $route, $params)) {
                    call_user_func($callback, ...$params);
                    return true;
                }
            }
        // Not found page
        call_user_func($this->getRoute('/404', 'get'));
        return false;
    }
}