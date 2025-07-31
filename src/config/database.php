<?php

use Dotenv\Dotenv;

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            if (class_exists('Dotenv\\Dotenv')) {
                $dotenv = Dotenv::createImmutable(dirname(ROOT_PATH));
                $dotenv->safeLoad();
            }

            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];
            $dbname = $_ENV['DB_NAME'];

            $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            echo "Database Connection Error: Contact the administrator.";
            exit;
        }
    }

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}