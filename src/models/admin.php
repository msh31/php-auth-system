<?php
class Admin
{
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllUsers() {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    u.id,
                    u.username,
                    u.email,
                    u.is_admin,
                    u.created_at,
                    COUNT(ug.id) as game_count,
                    SUM(ug.playtime_hours) as total_hours
                FROM users u
                LEFT JOIN user_games ug ON u.id = ug.user_id
                GROUP BY u.id
                ORDER BY u.created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching users: " . $e->getMessage());
            return [];
        }
    }

    public function getUserById($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return null;
        }
    }

    public function createUser($username, $email, $password, $isAdmin = 0) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);

            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'message' => 'Username or email already exists'];
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->db->prepare("
                INSERT INTO users (username, email, password, is_admin, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$username, $email, $hashedPassword, $isAdmin]);

            return ['success' => true, 'message' => 'User created successfully', 'user_id' => $this->db->lastInsertId()];
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to create user'];
        }
    }

    public function updateUser($userId, $data) {
        try {
            $updates = [];
            $params = [];

            if (isset($data['username'])) {
                $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
                $stmt->execute([$data['username'], $userId]);
                if ($stmt->fetch()) {
                    return ['success' => false, 'message' => 'Username already taken'];
                }
                $updates[] = "username = ?";
                $params[] = $data['username'];
            }

            if (isset($data['email'])) {
                $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$data['email'], $userId]);
                if ($stmt->fetch()) {
                    return ['success' => false, 'message' => 'Email already in use'];
                }
                $updates[] = "email = ?";
                $params[] = $data['email'];
            }

            if (isset($data['is_admin'])) {
                $updates[] = "is_admin = ?";
                $params[] = $data['is_admin'];
            }

            if (isset($data['password']) && !empty($data['password'])) {
                $updates[] = "password = ?";
                $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
            }

            if (empty($updates)) {
                return ['success' => true, 'message' => 'No changes to update'];
            }

            $params[] = $userId;
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return ['success' => true, 'message' => 'User updated successfully'];
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update user'];
        }
    }

    public function deleteUser($userId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            return ['success' => true, 'message' => 'User deleted successfully'];
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to delete user'];
        }
    }
}
