<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__FILE__));
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}

$autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
} else {
    die("Composer autoloader not found. Run 'composer install' first.");
}

if (session_status() == PHP_SESSION_NONE) {
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;");
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");

    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.gc_maxlifetime', 1800); // 30 minutes

    session_start();
}

spl_autoload_register(function ($class) {
    $controllerFile = ROOT_PATH . '/controllers/' . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $class)) . '.php';

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        return;
    }
    
    $modelFile = ROOT_PATH . '/models/' . strtolower($class) . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
    }
});

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/utils/helpers.php';
