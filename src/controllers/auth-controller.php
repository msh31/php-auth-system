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
            $validation = validateInputs(
                [
                    'username' => $_POST['username'] ?? '',
                    'password' => $_POST['password'] ?? ''
                ],
                [
                    'username' => 'username',
                    'password' => 'password'
                ]
            );
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!empty($validation['errors'])) {
                $error = implode(', ', $validation['errors']);
            } elseif(!validateCSRFToken($csrf_token)) {
                $error = "Invalid security token. Please try again.";
            } else {
                $username = $validation['values']['username'];
                $password = $validation['values']['password'];

                try {
                    $user = $this->userModel->login($username, $password);

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['is_admin'] = $user['is_admin'] ?? 0;
                    $_SESSION['logged_in'] = true;

                    session_regenerate_id(true);

                    $this->userModel->logUserActivity($user['id'], "login");
                    header('Location: /dashboard');
                    exit;
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    error_log("login error: " . $e->getMessage());
                }
            }
        }

        $csrf_token = generateCSRFToken();
        include ROOT_PATH . '/public/views/auth/login.php';
    }

    public function register() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validation = validateInputs(
                [
                    'username' => $_POST['username'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'password' => $_POST['password'] ?? ''
                ],
                [
                    'username' => 'username',
                    'email' => 'email', 
                    'password' => 'password'
                ]
            );

            $confirmPassword = $_POST['confirm-password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!empty($validation['errors'])) {
                $error = implode(', ', $validation['errors']);
            } elseif(!validateCSRFToken($csrf_token)) {
                $error = "Invalid security token. Please try again.";
            } else {
                $username = $validation['values']['username'];
                $email = $validation['values']['email'];
                $password = $validation['values']['password'];

                if ($password !== $confirmPassword) {
                    $error = "Passwords do not match.";
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
                                $_SESSION['is_admin'] = $user['is_admin'] ?? 0;
                                $_SESSION['logged_in'] = true;

                                session_regenerate_id(true);

                                $this->userModel->logUserActivity($user['id'], "registration");
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

        }

        $csrf_token = generateCSRFToken();
        include ROOT_PATH . '/public/views/auth/register.php';
    }

    public function logout() {
        $this->userModel->logUserActivity($_SESSION['user_id'], "logout");
        session_destroy();
        header('Location: ' . BASE_URL . 'home');
        exit;
    }
}
