CREATE TABLE IF NOT EXISTS `#__user_pro_custom_user` (
`id` int(11) UNSIGNED NOT NULL,
`user_id` int(11) NOT NULL,
`field_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
`image_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
`yoo_theme_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
`created_time` DATETIME NULL  DEFAULT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__user_pro_email_setting` (
`id` int(11) UNSIGNED NOT NULL,
`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`message` text COLLATE utf8mb4_unicode_ci NOT NULL,
`created_time` DATETIME NULL  DEFAULT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;