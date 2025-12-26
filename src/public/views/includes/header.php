<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/assets/css/output.css">
        <title>PHP Auth System</title>
    </head>
    <body>
        <div
            id="alertPlaceholder"
            class="fixed top-0 right-0 p-4 z-[1050] max-w-[350px]">
            <?php echo displayNotifications(); ?>
        </div>
