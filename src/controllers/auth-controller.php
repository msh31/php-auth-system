<?php
require_once ROOT_PATH . '/models/user.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!$this->validateCSRF($csrf_token)) {
                $error = "Invalid security token. Please try again.";
            } elseif (empty($username) || empty($password)) {
                $error = "Please fill in all fields.";
            } else {
                $user = $this->userModel->login($username, $password);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['logged_in'] = true;

                    header('Location: /dashboard');
                    exit;
                } else {
                    $error = "Invalid username or password";
                }
            }
        }

        $csrf_token = $this->generateCSRF();
        include ROOT_PATH . '/public/views/auth/login.php';
    }
    public function register() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm-password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!$this->validateCSRF($csrf_token)) {
                $error = "Invalid security token. Please try again.";
            } elseif (empty($username) || empty($email) || empty($password)) {
                $error = "Please fill in all fields.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Please enter a valid email.";
            } elseif ($password !== $confirmPassword) {
                $error = "Passwords do not match.";
            } elseif (strlen($password) < 8) {
                $error = "Password must be at least 8 characters.";
            } elseif(!$this->userModel->isStrongPassword($password)) {
                $error = "Password does not contain an UPPER, lower, number or special character!";
            } else {
                try {
                    $result = $this->userModel->register($username, $email, $password);

                    if ($result) {
                        $user = $this->userModel->login($username, $password);
                        if ($user) {
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['logged_in'] = true;

                            header('Location: /dashboard');
                            exit;
                        } else {
                            header('Location: login.php?success=1');
                            exit;
                        }
                    } else {
                        $error = "Registration failed. Please try again.";
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $csrf_token = $this->generateCSRF();
        include ROOT_PATH . '/public/views/auth/register.php';
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . 'home');
        exit;
    }

    private function generateCSRF() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    private function validateCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
