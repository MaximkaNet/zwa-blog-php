<?php
namespace app\config;

// Entry path
define('HOME_DIR', "");

// Prefix
define("PREFIX", "");

// Website name
define("WEBSITE_NAME", "CodeHub");

// Database username
define("DB_USERNAME", "root");

// Database password
define("DB_PASSWORD", "");

// Database host
define("DB_HOST", "localhost");

// Database name
define("DB_NAME", "zwa-blog");

define("USERS_TABLE", "
CREATE TABLE IF NOT EXISTS `users`(
    `id` int NOT NULL AUTO_INCREMENT,
    `email` varchar(60) NOT NULL UNIQUE,
    `password` varchar(60) NOT NULL,
    `first_name` varchar(60) NOT NULL,
    `last_name` varchar(60) NULL,
    `avatar` varchar(60) NULL,
    `role` enum('user', 'admin') NOT NULL DEFAULT 'user',
    `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` datetime NULL,
    PRIMARY KEY (`id`)
);
");

define("CATEGORIES_TABLE", "
CREATE TABLE IF NOT EXISTS `categories`(
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(30) NOT NULL UNIQUE,
    `display_name` varchar(20) NOT NULL,
    PRIMARY KEY (`id`)
);
");

define("POSTS_TABLE", "
CREATE TABLE IF NOT EXISTS `posts`(
    `id` int NOT NULL AUTO_INCREMENT,
    `title` varchar(300) NULL,
    `content` longtext NULL,
    `rating` int DEFAULT 0,
    `count_saved` int DEFAULT 0,
    `status` enum('draft', 'publish') NOT NULL DEFAULT 'draft',
    `user_id` int NOT NULL,
    `category_id` int NULL,
    `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` datetime NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION
);
");

define("COMMENTS_TABLE", "
CREATE TABLE IF NOT EXISTS `comments`(
    `id` int NOT NULL AUTO_INCREMENT,
    `content` mediumtext NOT NULL,
    `user_id` int NULL,
    `replay_to` int NULL,
    `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` datetime NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION
);
");