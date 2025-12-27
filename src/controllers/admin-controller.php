<?php
require_once ROOT_PATH . '/models/admin.php';

class AdminController
{
    private $adminModel;

    public function __construct() {
        $this->adminModel = new Admin();
    }

    public function index() {
        requireAdmin();

        $users = $this->adminModel->getAllUsers();

        require_once ROOT_PATH . '/public/views/admin/dashboard.php';
    }

    public function users() {
        requireAdmin();

        $users = $this->adminModel->getAllUsers();

        require_once ROOT_PATH . '/public/views/admin/users.php';
    }

    public function createUser() {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

            if (empty($username) || empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email address']);
                return;
            }

            if (strlen($password) < 6) {
                echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
                return;
            }

            $result = $this->adminModel->createUser($username, $email, $password, $isAdmin);
            echo json_encode($result);
            return;
        }

        require_once ROOT_PATH . '/public/views/admin/create-user.php';
    }

    public function editUser() {
        requireAdmin();

        $userId = $_GET['id'] ?? null;

        if (!$userId) {
            prepareNotification("error", "Invalid user ID");
            redirect(BASE_URL . 'admin/users');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'is_admin' => isset($_POST['is_admin']) ? 1 : 0
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }

            $result = $this->adminModel->updateUser($userId, $data);
            echo json_encode($result);
            return;
        }

        $user = $this->adminModel->getUserById($userId);

        if (!$user) {
            prepareNotification("error", "User not found");
            redirect(BASE_URL . 'admin/users');
            return;
        }

        require_once ROOT_PATH . '/public/views/admin/edit-user.php';
    }

    public function deleteUser() {
        requireAdmin();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $userId = $_POST['user_id'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'User ID required']);
            return;
        }

        if ($userId == $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
            return;
        }

        $result = $this->adminModel->deleteUser($userId);
        echo json_encode($result);
    }
}
