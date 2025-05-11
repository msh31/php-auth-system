<?php
require_once ROOT_PATH . '/models/user.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
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

            if (!empty($validation['errors'])) {
                foreach ($validation['errors'] as $field => $error) {
                    prepareNotification("error", $error);
                }
                $this->showLogin();
                return;
            }

            $username = $validation['values']['username'];
            $password = $validation['values']['password'];

            if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
                prepareNotification("error", "CSRF validation failed");
                $this->showLogin();
                return;
            }

            if (empty($username) || empty($password)) {
                prepareNotification("error", "Please fill in all fields.");
                $this->showLogin();
                return;
            }

            $user = $this->userModel->login($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;

                session_regenerate_id(true);

                logUserActivity($user['id'], 'Logged in');

                setSuccessMessage("Login successful!");
                redirect(BASE_URL . 'dashboard');
            } else {
                prepareNotification("error", "Invalid username or password");
                $this->showLogin();
                return;
            }
        }

        $this->showLogin();
    }

    public function showLogin() {
        require_once ROOT_PATH . '/public/views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validation = validateInputs(
                [
                    'username' => $_POST['username'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'password' => $_POST['password'] ?? '',
                    '$confirmPassword' => $_POST['confirm-password'] ?? ''
                ],
                [
                    'username' => 'username',
                    'email' => 'email',
                    'password' => 'password',
                    '$confirmPassword' => 'confirm_password'
                ]
            );

            if (!empty($validation['errors'])) {
                foreach ($validation['errors'] as $field => $error) {
                    prepareNotification("error", $error);
                }
                $this->showLogin();
                return;
            }

            $username = $validation['values']['username'];
            $email = $validation['values']['email'];
            $password = $validation['values']['password'];
            $confirmPassword = $validation['values']['confirm_password'];

            if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
                prepareNotification("error", "CSRF validation failed");
                $this->showRegister();
                return;
            }

            if (empty($username) || empty($email) || empty($password)) {
                prepareNotification("error", "Please fill in all fields.");
                $this->showRegister();
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                prepareNotification("error", "Invalid email format.");
                $this->showRegister();
                return;
            }

            if ($password !== $confirmPassword) {
                prepareNotification("error", "Passwords do not match.");
                $this->showRegister();
                return;
            }

            if (!$this->userModel->isStrongPassword($password)) {
                prepareNotification("error", "Password must be at least 8 characters and include uppercase, lowercase, numbers, and special characters.");
                $this->showRegister();
                return;
            }

            try {
                $result = $this->userModel->register($username, $email, $password);

                if ($result) {
                    $userId = $this->userModel->getLastInsertId();
                    logUserActivity($userId, 'Account created');

                    prepareNotification("success", "Registration successful!");
                    redirect(BASE_URL . 'login');
                } else {
                    prepareNotification("error", "Registration failed.");
                    $this->showRegister();
                    return;
                }
            } catch (Exception $e) {
                prepareNotification("error", $e->getMessage());
                $this->showRegister();
                return;
            }
        }

        $this->showRegister();
    }

    public function showRegister() {
        require_once ROOT_PATH . '/public/views/auth/register.php';
    }

    public function logout() {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        redirect(BASE_URL);
    }
}