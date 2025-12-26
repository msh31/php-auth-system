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

        $request = trim(str_replace($basePath, '', $request), '/');
        $key = $method . ' /' . $request;

        if(array_key_exists($key, $this->routes)) {
            $route = $this->routes[$key]; 
            $controllerName = $route['controller']; 
            $methodName = $route['method'];

            $controller = new $controllerName();
            $controller->$methodName();
        }
        else {
            echo "404 - Route not found";
            http_response_code(404);       
        }
    }
}
