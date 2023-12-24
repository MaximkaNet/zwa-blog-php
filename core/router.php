<?php
/**
 * PHP version 7.2
 */
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
    public function getPrefix()
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
            $normalized_route = Router::normalizePath($path, $this->prefix);
            // Get params
            preg_match_all("/:([^\/]+)/", $normalized_route, $param_keys);
            $regexp_route = $normalized_route;
            foreach ($param_keys[0] as $param) {
                $optional = ($param[strlen($param) - 1] === "?");
                $group = preg_replace("/[:?]+/", "", $param);
                $replacement = $optional ? "\/?(?<$group>[^\/]+)?" : "\/(?<$group>[^\/]+)";
                $pattern = $optional ? "/\/$param\?/" : "/\/$param/";
                $regexp_route = preg_replace($pattern, $replacement, $regexp_route);
            }
            $this->routes[$_method][$regexp_route] = [
                "callback" => $callback,
                "original" => $normalized_route
            ];
        }
    }

    /**
     * Get the route
     * @param string $path
     * @param string $method
     * @return mixed|null
     */
    public function getRoute(string $path, string $method)
    {
        foreach ($this->routes[strtoupper($method)] as $regexp_route => $route) {
            if($route["original"] === $path)
                return [$regexp_route => $route];
        }
        return null;
    }
    /**
     * Get request method
     * @return string
     */
    public static function getRequestMethod(): string
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
        return $parts[0] == ".." || $parts[0] == "." ? $normalized : ("/" . $normalized);
    }

    /**
     * Return absolute link like this: /absolute_link
     * @param string $path
     * @param string $prefix
     * @return string
     * @deprecated
     */
    public static function absoluteLink(string $path, string $prefix = ""): string
    {
        return Router::normalizePath($path, $prefix);
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
     * Get query param
     * @param string $key
     * @return string
     */
    public static function getQueryParam(string $key): string
    {
        return $_GET[$key];
    }

    /**
     * Match url with route.
     * @param string $url
     * @param string $route
     * @param array $params
     * @return false|int
     */
    public static function matchUrl(string $url, string $route, &$params)
    {
        // Check if the route matches the url
        $match_result = preg_match("#^" . $route ."$#", $url, $matches);
        // Get params
        $params = array_filter($matches, function ($key) {
            return is_string($key);
        }, ARRAY_FILTER_USE_KEY);
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
                    $call_result = call_user_func($callback, ...$params);
                    if(!empty($call_result)) echo $call_result;
                    return true;
                }
            }
        // Not found page
        $call_result = call_user_func($this->getRoute('/404', 'get'));
        if(!empty($call_result)) echo $call_result;
        return false;
    }
}