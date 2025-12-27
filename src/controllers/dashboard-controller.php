<?php
require_once ROOT_PATH . "/models/user.php";

class DashboardController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        if (!isLoggedIn()) {
            prepareNotification(
                "error",
                "Please log in to access the dashboard.",
            );
            redirect(BASE_URL . "login");
            return;
        }

        $error = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $validation = validateInputs(
                [
                    "new_username" => $_POST["new_username"] ?? "",
                    "current_password" => $_POST["current_password"] ?? "",
                ],
                [
                    "new_username" => "username",
                    "current_password" => "password",
                ],
            );

            $csrf_token = $_POST["csrf_token"] ?? "";

            if (!empty($validation["errors"])) {
                $error = implode(", ", $validation["errors"]);
            } elseif (!validateCSRFToken($csrf_token)) {
                $error = "Invalid security token. Please try again.";
            } else {
                $username = $validation["values"]["new_username"];
                $password = $validation["values"]["current_password"];

                try {
                    if (
                        $this->userModel->updateProfile(
                            $_SESSION["user_id"],
                            $username,
                            $password,
                        )
                    ) {
                        $this->userModel->logUserActivity(
                            $_SESSION["user_id"],
                            "update profile",
                        );

                        $_SESSION["username"] = $username;
                        prepareNotification(
                            "success",
                            "Profile updated successfully.",
                        );
                        redirect(BASE_URL . "dashboard");
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $userData = $this->userModel->getUserById($_SESSION["user_id"]);
        $userActivities = $this->userModel->getUserActivities(
            $_SESSION["user_id"],
            69,
        );

        require_once ROOT_PATH . "/public/views/dashboard.php";
    }
}
