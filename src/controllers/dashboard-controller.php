<?php
require_once ROOT_PATH . '/models/user.php';

class DashboardController
{
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        if (!isLoggedIn()) {
            prepareNotification("error", "Please log in to access the dashboard.");
            redirect(BASE_URL . 'login');
            return;
        }

        $userData = $this->userModel->getUserById($_SESSION['user_id']);
        $userActivities = $this->userModel->getUserActivities($_SESSION['user_id'], 69);

        require_once ROOT_PATH . '/public/views/dashboard.php';
    }
}
