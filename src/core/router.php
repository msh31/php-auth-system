<?php

class Router {
    private $routes = [];

    function get($path, $controller, $method) {
        $key = 'GET ' . $path;
        $this->routes[$key] = ['controller' => $controller, 'method' => $method];
    }

    function post($path, $controller, $method) {
        $key = 'POST ' . $path;
        $this->routes[$key] = ['controller' => $controller, 'method' => $method];
    }

    function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $request = $_SERVER['REQUEST_URI'];
        $basePath = '/';

        $request = parse_url($request, PHP_URL_PATH);
        $request = ltrim($request, '/'); 
        $key = $method . ' /' . $request;

        if(array_key_exists($key, $this->routes)) {
            $route = $this->routes[$key]; 
            $controllerName = $route['controller']; 
            $methodName = $route['method'];

            $controller = new $controllerName();
            $controller->$methodName();
        }
        else {
            http_response_code(404);       
        }
    }
}
