-- ============================================================
-- DSKM DAKHIL 2010 & ALIM 2012 ALUMNI PORTAL
-- Complete MySQL Database Schema
-- Version: 1.0.0
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+06:00";

CREATE DATABASE IF NOT EXISTS `dskm_portal`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `dskm_portal`;

-- ============================================================
-- TABLE: roles
-- ============================================================
CREATE TABLE IF NOT EXISTS `roles` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(50) NOT NULL,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_roles_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`name`, `slug`, `description`) VALUES
('Super Admin', 'super_admin', 'Full system access'),
('Admin', 'admin', 'Administrative access'),
('Moderator', 'moderator', 'Content moderation access'),
('Member', 'member', 'Standard member access');

-- ============================================================
-- TABLE: permissions
-- ============================================================
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `module` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_permissions_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`name`, `slug`, `module`) VALUES
('View Members','view_members','members'),
('Edit Members','edit_members','members'),
('Delete Members','delete_members','members'),
('Approve Members','approve_members','members'),
('Create Events','create_events','events'),
('Edit Events','edit_events','events'),
('Delete Events','delete_events','events'),
('Approve Events','approve_events','events'),
('Create Notices','create_notices','notices'),
('Edit Notices','edit_notices','notices'),
('Delete Notices','delete_notices','notices'),
('Manage Gallery','manage_gallery','gallery'),
('Manage Support','manage_support','support'),
('Manage Memorial','manage_memorial','memorial'),
('View Admin Panel','view_admin','admin'),
('Manage Settings','manage_settings','admin'),
('View Reports','view_reports','admin');

-- ============================================================
-- TABLE: role_permissions
-- ============================================================
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` TINYINT UNSIGNED NOT NULL,
  `permission_id` SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: batches
-- ============================================================
CREATE TABLE IF NOT EXISTS `batches` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `year` YEAR NOT NULL,
  `exam_type` ENUM('dakhil','alim') NOT NULL,
  `description` TEXT,
  `is_active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `batches` (`name`, `year`, `exam_type`) VALUES
('Dakhil 2010', 2010, 'dakhil'),
('Alim 2012', 2012, 'alim');

-- ============================================================
-- TABLE: users
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(150),
  `mobile` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role_id` TINYINT UNSIGNED NOT NULL DEFAULT 4,
  `status` ENUM('pending','active','suspended','rejected') NOT NULL DEFAULT 'pending',
  `email_verified_at` TIMESTAMP NULL,
  `remember_token` VARCHAR(100),
  `last_login_at` TIMESTAMP NULL,
  `last_active_at` TIMESTAMP NULL,
  `is_online` TINYINT(1) DEFAULT 0,
  `login_attempts` TINYINT DEFAULT 0,
  `locked_until` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_users_username` (`username`),
  UNIQUE KEY `uk_users_email` (`email`),
  UNIQUE KEY `uk_users_mobile` (`mobile`),
  KEY `idx_users_status` (`status`),
  KEY `idx_users_role` (`role_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: profiles
-- ============================================================
CREATE TABLE IF NOT EXISTS `profiles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `full_name_bn` VARCHAR(150) NOT NULL,
  `full_name_en` VARCHAR(150) NOT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `cover_photo` VARCHAR(255) DEFAULT NULL,
  `blood_group` ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `date_of_birth` DATE DEFAULT NULL,
  `gender` ENUM('male','female','other') DEFAULT NULL,
  `whatsapp` VARCHAR(20) DEFAULT NULL,
  `address_present` TEXT,
  `address_permanent` TEXT,
  `district` VARCHAR(100),
  `country` VARCHAR(100) DEFAULT 'Bangladesh',
  `profession` VARCHAR(150),
  `workplace` VARCHAR(200),
  `bio` TEXT,
  `facebook_url` VARCHAR(300),
  `linkedin_url` VARCHAR(300),
  `hide_phone` TINYINT(1) DEFAULT 0,
  `hide_email` TINYINT(1) DEFAULT 0,
  `members_only` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_profiles_user` (`user_id`),
  KEY `idx_profiles_district` (`district`),
  KEY `idx_profiles_blood` (`blood_group`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: user_batches (many-to-many: user can have both batches)
-- ============================================================
CREATE TABLE IF NOT EXISTS `user_batches` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `batch_id` TINYINT UNSIGNED NOT NULL,
  `roll_number` VARCHAR(20),
  `registration_number` VARCHAR(30),
  `gpa` DECIMAL(3,2),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_batch` (`user_id`, `batch_id`),
  KEY `idx_ub_batch` (`batch_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`batch_id`) REFERENCES `batches`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: password_resets
-- ============================================================
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `mobile` VARCHAR(20),
  `email` VARCHAR(150),
  `token` VARCHAR(100) NOT NULL,
  `expires_at` TIMESTAMP NOT NULL,
  `used` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pr_token` (`token`),
  KEY `idx_pr_mobile` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: events
-- ============================================================
CREATE TABLE IF NOT EXISTS `events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(300) NOT NULL,
  `slug` VARCHAR(350) NOT NULL,
  `description` LONGTEXT,
  `banner` VARCHAR(255),
  `event_date` DATETIME NOT NULL,
  `event_end_date` DATETIME,
  `venue` VARCHAR(300),
  `venue_map_url` VARCHAR(500),
  `max_attendees` INT DEFAULT NULL,
  `status` ENUM('draft','published','cancelled','completed') DEFAULT 'draft',
  `created_by` INT UNSIGNED NOT NULL,
  `approved_by` INT UNSIGNED DEFAULT NULL,
  `approved_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_events_slug` (`slug`),
  KEY `idx_events_date` (`event_date`),
  KEY `idx_events_status` (`status`),
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
  FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: event_rsvp
-- ============================================================
CREATE TABLE IF NOT EXISTS `event_rsvp` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `status` ENUM('going','maybe','not_going') DEFAULT 'going',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_rsvp` (`event_id`, `user_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: notices
-- ============================================================
CREATE TABLE IF NOT EXISTS `notices` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(300) NOT NULL,
  `slug` VARCHAR(350) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `category` ENUM('general','academic','event','urgent','other') DEFAULT 'general',
  `attachment` VARCHAR(255),
  `is_pinned` TINYINT(1) DEFAULT 0,
  `status` ENUM('draft','published') DEFAULT 'draft',
  `created_by` INT UNSIGNED NOT NULL,
  `views` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_notices_slug` (`slug`),
  KEY `idx_notices_status` (`status`),
  KEY `idx_notices_pinned` (`is_pinned`),
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: notice_comments
-- ============================================================
CREATE TABLE IF NOT EXISTS `notice_comments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `notice_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `comment` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_nc_notice` (`notice_id`),
  FOREIGN KEY (`notice_id`) REFERENCES `notices`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: messages (direct messaging)
-- ============================================================
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` VARCHAR(50) NOT NULL,
  `sender_id` INT UNSIGNED NOT NULL,
  `receiver_id` INT UNSIGNED NOT NULL,
  `message` TEXT,
  `media` VARCHAR(255),
  `media_type` ENUM('image','file','audio') DEFAULT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `read_at` TIMESTAMP NULL,
  `deleted_by_sender` TINYINT(1) DEFAULT 0,
  `deleted_by_receiver` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_messages_conversation` (`conversation_id`),
  KEY `idx_messages_sender` (`sender_id`),
  KEY `idx_messages_receiver` (`receiver_id`),
  KEY `idx_messages_read` (`is_read`),
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: conversations
-- ============================================================
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` VARCHAR(50) NOT NULL,
  `user1_id` INT UNSIGNED NOT NULL,
  `user2_id` INT UNSIGNED NOT NULL,
  `last_message_id` INT UNSIGNED DEFAULT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_conv_user1` (`user1_id`),
  KEY `idx_conv_user2` (`user2_id`),
  FOREIGN KEY (`user1_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user2_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: galleries (albums)
-- ============================================================
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(250) NOT NULL,
  `description` TEXT,
  `cover_image` VARCHAR(255),
  `category` VARCHAR(100),
  `created_by` INT UNSIGNED NOT NULL,
  `status` ENUM('active','inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_galleries_slug` (`slug`),
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: gallery_images
-- ============================================================
CREATE TABLE IF NOT EXISTS `gallery_images` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `gallery_id` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(300),
  `uploaded_by` INT UNSIGNED NOT NULL,
  `likes` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_gi_gallery` (`gallery_id`),
  FOREIGN KEY (`gallery_id`) REFERENCES `galleries`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: smoronika (memories/articles)
-- ============================================================
CREATE TABLE IF NOT EXISTS `smoronika` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(300) NOT NULL,
  `slug` VARCHAR(350) NOT NULL,
  `content` LONGTEXT,
  `image` VARCHAR(255),
  `author_id` INT UNSIGNED NOT NULL,
  `status` ENUM('draft','pending','published','rejected') DEFAULT 'pending',
  `approved_by` INT UNSIGNED DEFAULT NULL,
  `approved_at` TIMESTAMP NULL,
  `views` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_smoronika_slug` (`slug`),
  KEY `idx_smoronika_status` (`status`),
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: memorials
-- ============================================================
CREATE TABLE IF NOT EXISTS `memorials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(200) NOT NULL,
  `full_name_bn` VARCHAR(200),
  `photo` VARCHAR(255),
  `type` ENUM('member','teacher','staff') DEFAULT 'member',
  `batch_id` TINYINT UNSIGNED DEFAULT NULL,
  `date_of_death` DATE,
  `description` TEXT,
  `tributes` INT DEFAULT 0,
  `added_by` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_memorials_type` (`type`),
  FOREIGN KEY (`added_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: support_requests
-- ============================================================
CREATE TABLE IF NOT EXISTS `support_requests` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `category` ENUM('financial','medical','personal','other') NOT NULL,
  `subject` VARCHAR(300) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `attachment` VARCHAR(255),
  `status` ENUM('pending','reviewing','approved','rejected','resolved') DEFAULT 'pending',
  `admin_note` TEXT,
  `reviewed_by` INT UNSIGNED DEFAULT NULL,
  `reviewed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sr_status` (`status`),
  KEY `idx_sr_user` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`reviewed_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: notifications
-- ============================================================
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `body` TEXT,
  `link` VARCHAR(300),
  `icon` VARCHAR(50),
  `is_read` TINYINT(1) DEFAULT 0,
  `read_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notif_user` (`user_id`),
  KEY `idx_notif_read` (`is_read`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: activity_log
-- ============================================================
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED,
  `action` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_al_user` (`user_id`),
  KEY `idx_al_action` (`action`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: site_settings
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key_name` VARCHAR(100) NOT NULL,
  `value` TEXT,
  `group_name` VARCHAR(50) DEFAULT 'general',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_settings_key` (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`key_name`, `value`, `group_name`) VALUES
('site_name', 'DSKM Batch Portal', 'general'),
('site_tagline', 'Dakhil 2010 & Alim 2012 Alumni', 'general'),
('contact_email', 'noreply@demo.dskm.org', 'general'),
('contact_phone', '01700000000', 'general'),
('registration_open', '1', 'general'),
('require_admin_approval', '1', 'general'),
('max_upload_size', '5242880', 'general'),
('primary_color', '#0B1F3A', 'theme'),
('accent_color', '#D4AF37', 'theme');

-- ============================================================
-- Seed: Default Super Admin user
-- ============================================================
-- Password: Admin@1234 (bcrypt hashed)
INSERT INTO `users` (`username`, `email`, `mobile`, `password`, `role_id`, `status`, `email_verified_at`) VALUES
('superadmin', 'noreply@demo.dskm.org', '01700000000',
 '$2y$12$Z32p9fTQ6baM9XA/jONgT.ULI6DJUmv9UaxtiXQP.8LiaxkEsaa2q', 1, 'active', NOW());

INSERT INTO `profiles` (`user_id`, `full_name_bn`, `full_name_en`, `profession`) VALUES
(1, 'সুপার অ্যাডমিন', 'Super Admin', 'System Administrator');

SET FOREIGN_KEY_CHECKS = 1;
