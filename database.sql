CREATE DATABASE IF NOT EXISTS `php_auth_db`;
USE `php_auth_db`;

CREATE TABLE `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `is_admin` TINYINT(1) DEFAULT 0,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` DATETIME DEFAULT NOW(),
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS user_activities (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    activity_type VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    created_at DATETIME DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
