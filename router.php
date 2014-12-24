<?php

 /**
 * PHP Router
 *
 * @package    PHP Router
 * @version    0.1 PHP 5 >= 5.4.0
 * @author     Alexandros Nikiforidis <nikiforidis.alex@gmail.com>
 * @license    GPL3
 */


class Router {

    /**
    * Array that holds all routes
    * @var array
    */
    protected $routes;

    /**
    * Array that holds all HTTP methods allowed
    * @var array
    */
    protected $methods_allowed;

    /**
    * Array that holds all urls parameters
    * @var array
    */
    protected $url_params;

    /**
    * String that holds target controller name
    * @var string
    */
    protected $controller;

    protected $base_path;

    public function __construct() {

    }

    /**
    * Get routes from json file
    * @param string $file Path to json file
    * @return array $routes
    */
    public function get_routes_from_file($file) {
        $this->routes = json_decode(file_get_contents(__DIR__.$file), true);
        return $this->routes;
    }

    /**
    * Get routes everytime you want to register a new route
    * @param array $method Array of HTTP methods a route is allowed to be called
    * @param string $route String with a regex to match the requested URI
    * @param string $controller String with the name of the controller to be called
    * @return array $routes
    */
    public function register($method, $route, $controller) {
        $this->routes[] = array($method, $route, $controller);
        return $this->routes;
    }

    /**
    * Dispatcher to be called when all routes have been matched
    */
    public function dispatch() {
        $this->attach();
        $this->call_controller();
    }

    /**
    * Match routes against current URI
    */
    private function attach() {
        // error_log(print_r($this->routes, true));

        $request = $this->get_request();

        foreach ($this->routes as $route) {
            $escaped_route = $this->get_escaped_regex_route($route[1]);
            // error_log(print_r($escaped_route, true));
            if(preg_match($escaped_route, $request, $matches)) {
                // error_log(print_r($route[0], true));
                $this->check_method($route[0]);

                foreach($matches as $key => $match) {
                    if(is_string($key)) {
                        $this->url_params[$key] = $match;
                    }
                }
                // error_log(print_r($this->url_params, true));
                $this->controller = $route[2];
            }
        }
    }

    /**
    * Get a slashes-escaped version of the regexed route to match
    * @param string $route String with a regex to match the requested URI
    * @return string
    */
    private function get_escaped_regex_route($route) {
        return '/'.str_replace('/', '\/', $route).'/';
    }

    /**
    * Get the requested uri string and append a slashe
    * @return string
    */
    private function get_request() {
        if(!empty($_SERVER['REQUEST_URL'])) {
            $request = rtrim($_SERVER['REQUEST_URL'], '/') . '/';
        }
        else {
            $request = rtrim($_SERVER['REQUEST_URI'], '/') . '/';
            // error_log($request);
        }

        return $request;
    }

    /**
    * Function to call after all routes have been matched
    * It calls specific controller along with url params found
    */
    private function call_controller() {
        if(isset($this->url_params)){
            return call_user_func_array($this->controller, $this->url_params);
        }

        call_user_func($this->controller);
    }

    /**
    * Check sended method against allowed ones
    * If not allowed set "405 METHOD NOT ALLOWED" header and thow Excpetion
    */
    private function check_method($route_method) {
        // error_log(print_r($route_method, true));
        if (is_array($route_method) && in_array($_SERVER['REQUEST_METHOD'], $route_method)) {
            return true;
        }
        elseif (is_string($route_method) && ($_SERVER['REQUEST_METHOD'] == $route_method)) {
            return true;
        }

        http_response_code(405);
        throw new Exception("METHOD NOT ALLOWED", 1);
    }
}
