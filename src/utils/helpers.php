<?php
function h($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function sanitizeInput($input, $type = 'text') {
    $input = trim($input);

    switch ($type) {
        case 'email':
            $sanitized = filter_var($input, FILTER_SANITIZE_EMAIL);
            return filter_var($sanitized, FILTER_VALIDATE_EMAIL) ? $sanitized : false;

        case 'username':
            return preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $input) ? $input : false;

        case 'password':
            return strlen($input) >= 8 ? $input : false;

        case 'int':
            return filter_var($input, FILTER_VALIDATE_INT) !== false ?
                filter_var($input, FILTER_SANITIZE_NUMBER_INT) : false;

        case 'float':
            return filter_var($input, FILTER_VALIDATE_FLOAT) !== false ?
                filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : false;

        case 'text':
        default:
            return h($input);
    }
}

function validateInputs($inputs, $types) {
    $result = [
        'values' => [],
        'errors' => []
    ];

    foreach ($inputs as $field => $value) {
        $type = $types[$field] ?? 'text';
        $sanitized = sanitizeInput($value, $type);

        if ($sanitized === false) {
            $result['errors'][$field] = "Invalid $field format";
        } else {
            $result['values'][$field] = $sanitized;
        }
    }

    return $result;
}

function redirect($location) {
    header("Location: $location");
    exit;
}

function setErrorMessage($message) {
    $_SESSION['error_message'] = $message;
}

function setSuccessMessage($message) {
    $_SESSION['success_message'] = $message;
}

function prepareNotification($type, $message) {
    if (!isset($_SESSION['notifications'])) {
        $_SESSION['notifications'] = [];
    }

    $_SESSION['notifications'][] = [
        'type' => $type,
        'message' => $message
    ];
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

function checkSessionTimeout() {
    $max_idle_time = 1800;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $max_idle_time)) {
        session_unset();
        session_destroy();
        redirect(BASE_URL . "login?timeout=1");
    }
    $_SESSION['last_activity'] = time();
}

function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
}

function debug($var, $die = true) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    if ($die) die();
}

function logUserActivity($userId, $activityType, $ipAddress = null) {
    $database = Database::getInstance();
    $db = $database->getConnection();

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
        $stmt = $db->prepare("
            INSERT INTO user_activities (user_id, activity_type, ip_address)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$userId, $activityType, $ipAddress]);
    } catch (PDOException $e) {
        error_log("Error logging user activity: " . $e->getMessage());
        return false;
    }
}

function getUserActivities($userId, $limit = 5) {
    $database = Database::getInstance();
    $db = $database->getConnection();

    try {
        $stmt = $db->prepare("
            SELECT activity_type, ip_address, created_at
            FROM user_activities
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching user activities: " . $e->getMessage());
        return [];
    }
}

function addNotification(string $message, string $type = 'success') {
    if (!isset($_SESSION['notifications'])) {
        $_SESSION['notifications'] = [];
    }
    $_SESSION['notifications'][] = [
        'message' => $message,
        'type' => $type
    ];
}

function displayNotifications(): string {
    $output = '';
    if (!empty($_SESSION['notifications'])) {
        foreach ($_SESSION['notifications'] as $note) {
            $type = $note['type'] === 'error' ? 'error' : 'success';
            $color = $type === 'error' ? 'bg-red-600' : 'bg-green-600';
            $icon = $type === 'error'
                ? '<i class="fa fa-exclamation-circle"></i>'
                : '<i class="fa fa-check-circle"></i>';

            $output .= '
                <div class="notification ' . $color . ' relative w-full mb-4 p-4 pr-10 rounded-lg shadow-lg text-white flex items-start transition-transform duration-300 transform -translate-x-full" role="alert">
                    <span class="mr-3">' . $icon . '</span>
                    <span class="flex-1">' . htmlspecialchars($note['message']) . '</span>
                    <button class="absolute top-2 right-3 text-white text-lg font-bold hover:text-gray-200">&times;</button>
                </div>
            ';
        }
        unset($_SESSION['notifications']);
    }
    return $output;
}
