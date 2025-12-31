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

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $request = $_SERVER['REQUEST_URI'];
        $request = parse_url($request, PHP_URL_PATH);
        $request = '/' . ltrim($request, '/');

        foreach ($this->routes as $routeKey => $route) {
            list($routeMethod, $routePath) = explode(' ', $routeKey, 2);

            if ($routeMethod !== $method) continue;

            $pattern = preg_replace('/:\w+/', '([^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $request, $matches)) {
                array_shift($matches); 

                $controller = new $route['controller']();
                call_user_func_array([$controller, $route['method']], $matches);
                return;
            }
        }

        http_response_code(404);
    }
}
