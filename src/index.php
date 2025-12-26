<?php
require_once __DIR__ . '/bootstrap.php';
require_once ROOT_PATH . '/core/router.php';

require_once ROOT_PATH . '/controllers/auth-controller.php';
require_once ROOT_PATH . '/controllers/dashboard-controller.php';
require_once ROOT_PATH . '/controllers/home-controller.php';

checkSessionTimeout();
$router = new Router();

// auth routes
$router->get('/login', 'AuthController', 'login');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'register');
$router->post('/register', 'AuthController', 'register');
$router->get('/logout', 'AuthController', 'logout');

// views routes
$router->get('/', 'HomeController', 'index');
$router->get('/home', 'HomeController', 'index');
$router->get('/dashboard', 'DashboardController', 'index');

$router->dispatch();
