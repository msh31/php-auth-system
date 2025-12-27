<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function login($username, $password) {
        try {
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    public function register($username, $email, $password) {
        try {
            $sql = "SELECT COUNT(*) FROM users WHERE username = ? OR email = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$username, $email]);

            if ($stmt->fetchColumn() > 0) {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                $stmt->execute([$username]);

                if ($stmt->fetchColumn() > 0) {
                    throw new Exception("Username already taken.");
                } else {
                    throw new Exception("Email already registered.");
                }
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$username, $email, $hashedPassword]);

            return $result;
        } catch (Exception $e) {
            throw $e;
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            throw new Exception("Database error occurred during registration.");
        }
    }

    public function getUserById($id) {
        try {
            $sql = "SELECT id, username, email, created_at FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return false;
        }
    }

    public function isStrongPassword($password) {
        return strlen($password) >= 8 &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password) &&
            preg_match('/[^A-Za-z0-9]/', $password);
    }

    public function getLastInsertId() {
        try {
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error getting last insert ID: " . $e->getMessage());
            return false;
        }
    }

    public function getUserActivities($userId, $limit = 5) {
        try {
            $sql = "
            SELECT activity_type, ip_address, created_at
            FROM user_activities
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ?
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $userId, PDO::PARAM_INT);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user activities: " . $e->getMessage());
            return [];
        }
    }

    public function logUserActivity($userId, $activityType, $ipAddress = null) {
        if ($ipAddress === null) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 
                $_SERVER['HTTP_X_REAL_IP'] ?? 
                    $_SERVER['HTTP_CLIENT_IP'] ?? 
                        $_SERVER['REMOTE_ADDR'] ?? 
                            'unknown';

            if (strpos($ipAddress, ',') !== false) {
                $ipAddress = trim(explode(',', $ipAddress)[0]);
            }
        }
        try {
            $sql = "
                INSERT INTO user_activities (user_id, activity_type, ip_address)
            VALUES (?, ?, ?)
            ";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$userId, $activityType, $ipAddress]);
        } catch (PDOException $e) {
            error_log("Error logging user activity: " . $e->getMessage());
            return false;
        }
    }
}
