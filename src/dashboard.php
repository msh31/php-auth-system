<?php
require_once 'config.php';



function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
}

if (!isLoggedIn()) {
    redirect("login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>
<body class="bg-gray-600">
<div id="alertPlaceholder"></div>

</body>
</html>