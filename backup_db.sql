-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table db_progress.construction_document
CREATE TABLE IF NOT EXISTS `construction_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `discipline` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `checker` varchar(100) DEFAULT NULL,
  `reviewer` varchar(100) DEFAULT NULL,
  `approver` varchar(100) DEFAULT NULL,
  `uploader` varchar(100) DEFAULT NULL,
  `email_check` varchar(50) DEFAULT NULL,
  `email_review` varchar(50) DEFAULT NULL,
  `email_approve` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.construction_document: ~0 rows (approximately)

-- Dumping structure for table db_progress.construction_document_history
CREATE TABLE IF NOT EXISTS `construction_document_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `construction_document_id` int(11) DEFAULT NULL,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `extension` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `discipline` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL,
  `ext` varchar(30) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `approver` varchar(100) DEFAULT NULL,
  `reviewer` varchar(100) DEFAULT NULL,
  `checker` varchar(100) DEFAULT NULL,
  `uploader` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.construction_document_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.cor_surat_keluar
CREATE TABLE IF NOT EXISTS `cor_surat_keluar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `recipient` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `attn` varchar(200) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `hardcopy` varchar(20) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `category` varchar(5) DEFAULT NULL,
  `version` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.cor_surat_keluar: ~0 rows (approximately)

-- Dumping structure for table db_progress.cor_surat_keluar_history
CREATE TABLE IF NOT EXISTS `cor_surat_keluar_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `cor_surat_keluar_id` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `recipient` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `attn` varchar(200) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `hardcopy` varchar(20) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `category` varchar(5) DEFAULT NULL,
  `version` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.cor_surat_keluar_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.cor_surat_masuk
CREATE TABLE IF NOT EXISTS `cor_surat_masuk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `typeofincomingdocument` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `from_` varchar(200) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `category` varchar(5) DEFAULT NULL,
  `version` varchar(30) DEFAULT NULL,
  `hardcopy` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.cor_surat_masuk: ~0 rows (approximately)

-- Dumping structure for table db_progress.cor_surat_masuk_history
CREATE TABLE IF NOT EXISTS `cor_surat_masuk_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `cor_surat_masuk_id` varchar(100) DEFAULT NULL,
  `typeofincomingdocument` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `from_` varchar(200) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `category` varchar(5) DEFAULT NULL,
  `version` varchar(30) DEFAULT NULL,
  `hardcopy` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.cor_surat_masuk_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.document_engineer
CREATE TABLE IF NOT EXISTS `document_engineer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `discipline` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `checker` varchar(100) DEFAULT NULL,
  `reviewer` varchar(100) DEFAULT NULL,
  `approver` varchar(100) DEFAULT NULL,
  `uploader` varchar(100) DEFAULT NULL,
  `email_check` varchar(30) DEFAULT NULL,
  `email_review` varchar(30) DEFAULT NULL,
  `email_approve` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.document_engineer: ~0 rows (approximately)

-- Dumping structure for table db_progress.document_engineer_history
CREATE TABLE IF NOT EXISTS `document_engineer_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_engineer_id` int(11) DEFAULT NULL,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `extension` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `discipline` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL,
  `ext` varchar(30) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `approver` varchar(100) DEFAULT NULL,
  `reviewer` varchar(100) DEFAULT NULL,
  `checker` varchar(100) DEFAULT NULL,
  `uploader` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.document_engineer_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table db_progress.field_instruction
CREATE TABLE IF NOT EXISTS `field_instruction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `discipline` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `checker` varchar(100) DEFAULT NULL,
  `reviewer` varchar(100) DEFAULT NULL,
  `approver` varchar(100) DEFAULT NULL,
  `uploader` varchar(100) DEFAULT NULL,
  `email_check` varchar(50) DEFAULT NULL,
  `email_review` varchar(50) DEFAULT NULL,
  `email_approve` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.field_instruction: ~0 rows (approximately)

-- Dumping structure for table db_progress.field_instruction_history
CREATE TABLE IF NOT EXISTS `field_instruction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_instruction_id` int(11) DEFAULT NULL,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `extension` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `discipline` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL,
  `ext` varchar(30) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `approver` varchar(100) DEFAULT NULL,
  `reviewer` varchar(100) DEFAULT NULL,
  `checker` varchar(100) DEFAULT NULL,
  `uploader` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.field_instruction_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.links
CREATE TABLE IF NOT EXISTS `links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `source` int(11) NOT NULL,
  `target` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.links: ~0 rows (approximately)

-- Dumping structure for table db_progress.master_category
CREATE TABLE IF NOT EXISTS `master_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.master_category: ~10 rows (approximately)
INSERT INTO `master_category` (`id`, `description`, `category`) VALUES
	(1, 'Basic Design', 'engineering'),
	(2, 'Detail Engineering Design', 'engineering'),
	(3, 'Site Instruction', 'surat'),
	(4, 'Daily Report\n', 'surat'),
	(5, 'Weekly Report\n', 'surat'),
	(6, 'Monthly Report', 'surat'),
	(7, 'Planned', 'schedule_management'),
	(8, 'Actual', 'schedule_management'),
	(9, 'Engineering', 's_curve'),
	(10, 'Procurement', 's_curve'),
	(11, 'Construction', 's_curve'),
	(12, 'Commissioning', 's_curve');

-- Dumping structure for table db_progress.master_discipline
CREATE TABLE IF NOT EXISTS `master_discipline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.master_discipline: ~2 rows (approximately)
INSERT INTO `master_discipline` (`id`, `description`, `category`) VALUES
	(1, 'Discipline 1', NULL),
	(2, 'Discipline 2', NULL),
	(3, 'Discipline 3', NULL);

-- Dumping structure for table db_progress.master_status
CREATE TABLE IF NOT EXISTS `master_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.master_status: ~4 rows (approximately)
INSERT INTO `master_status` (`id`, `code`, `description`) VALUES
	(1, 'new', 'New'),
	(2, 'check', 'Check'),
	(3, 'review', 'Review'),
	(4, 'approve', 'Approve'),
	(5, 'notapprove', 'Not Approve');

-- Dumping structure for table db_progress.material_receiving_report
CREATE TABLE IF NOT EXISTS `material_receiving_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.material_receiving_report: ~0 rows (approximately)

-- Dumping structure for table db_progress.material_receiving_report_history
CREATE TABLE IF NOT EXISTS `material_receiving_report_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `mrr_id` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.material_receiving_report_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.material_verification_report
CREATE TABLE IF NOT EXISTS `material_verification_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.material_verification_report: ~0 rows (approximately)

-- Dumping structure for table db_progress.material_verification_report_history
CREATE TABLE IF NOT EXISTS `material_verification_report_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `mvr_id` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.material_verification_report_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.migrations: ~6 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2025_02_09_100502_create_tasks_table', 2),
	(6, '2025_02_09_100504_create_links_table', 2),
	(7, '2025_02_11_075016_create_permission_tables', 3);

-- Dumping structure for table db_progress.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table db_progress.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.model_has_roles: ~1 rows (approximately)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1);

-- Dumping structure for table db_progress.mom
CREATE TABLE IF NOT EXISTS `mom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofmeeting` varchar(50) DEFAULT NULL,
  `meetinglocation` varchar(50) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.mom: ~0 rows (approximately)

-- Dumping structure for table db_progress.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table db_progress.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.permissions: ~33 rows (approximately)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view_doc_engineering_upload', 'web', NULL, NULL),
	(2, 'view_doc_engineering_check', 'web', NULL, NULL),
	(3, 'view_doc_engineering_review', 'web', NULL, NULL),
	(4, 'view_doc_engineering_approve', 'web', NULL, NULL),
	(5, 'view_dashboard', 'web', NULL, NULL),
	(6, 'view_letter', 'web', NULL, NULL),
	(7, 'view_doc_schedule', 'web', NULL, NULL),
	(8, 'view_input_s_curve', 'web', NULL, NULL),
	(9, 'view_s_curve', 'web', NULL, NULL),
	(10, 'view_progress', 'web', NULL, NULL),
	(11, 'view_sop', 'web', NULL, NULL),
	(12, 'view_doc_engineering_mdr', 'web', NULL, NULL),
	(13, 'view_doc_engineering_basic_design', 'web', NULL, NULL),
	(14, 'view_doc_engineering_ded', 'web', NULL, NULL),
	(15, 'view_construction_upload', 'web', NULL, NULL),
	(16, 'view_construction_check', 'web', NULL, NULL),
	(17, 'view_construction_review', 'web', NULL, NULL),
	(18, 'view_construction_approve', 'web', NULL, NULL),
	(19, 'view_construction_document', 'web', NULL, NULL),
	(20, 'view_field_instruction_upload', 'web', NULL, NULL),
	(21, 'view_field_instruction_check', 'web', NULL, NULL),
	(22, 'view_field_instruction_review', 'web', NULL, NULL),
	(23, 'view_field_instruction', 'web', NULL, NULL),
	(24, 'view_correspondence_surat_masuk', 'web', NULL, NULL),
	(25, 'view_correspondence_surat_keluar', 'web', NULL, NULL),
	(26, 'view_daily_report', 'web', NULL, NULL),
	(27, 'view_weekly_report', 'web', NULL, NULL),
	(28, 'view_monthly_report', 'web', NULL, NULL),
	(29, 'view_minutes_of_meeting', 'web', NULL, NULL),
	(30, 'view_field_instruction_approve', 'web', NULL, NULL),
	(40, 'view_rfi', 'web', NULL, NULL),
	(41, 'view_mvr', 'web', NULL, NULL),
	(42, 'view_mrr', 'web', NULL, NULL);

-- Dumping structure for table db_progress.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table db_progress.report_daily
CREATE TABLE IF NOT EXISTS `report_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.report_daily: ~0 rows (approximately)

-- Dumping structure for table db_progress.report_daily_history
CREATE TABLE IF NOT EXISTS `report_daily_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `report_daily_id` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.report_daily_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.report_monthly
CREATE TABLE IF NOT EXISTS `report_monthly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(50) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.report_monthly: ~0 rows (approximately)

-- Dumping structure for table db_progress.report_monthly_history
CREATE TABLE IF NOT EXISTS `report_monthly_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `report_monthly_id` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(50) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.report_monthly_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.report_weekly
CREATE TABLE IF NOT EXISTS `report_weekly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(50) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.report_weekly: ~0 rows (approximately)

-- Dumping structure for table db_progress.report_weekly_history
CREATE TABLE IF NOT EXISTS `report_weekly_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `report_weekly_id` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(50) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.report_weekly_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.rfi
CREATE TABLE IF NOT EXISTS `rfi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.rfi: ~0 rows (approximately)

-- Dumping structure for table db_progress.rfi_history
CREATE TABLE IF NOT EXISTS `rfi_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `rfi_id` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `typeofreport` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.rfi_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.roles: ~7 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'superadmin', 'web', NULL, NULL),
	(2, 'admin', 'web', NULL, NULL),
	(3, 'approver', 'web', NULL, NULL),
	(4, 'checker', 'web', NULL, NULL),
	(5, 'reviewer', 'web', NULL, NULL),
	(6, 'user', 'web', NULL, NULL);

-- Dumping structure for table db_progress.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.role_has_permissions: ~35 rows (approximately)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(17, 1),
	(18, 1),
	(19, 1),
	(20, 1),
	(21, 1),
	(22, 1),
	(23, 1),
	(24, 1),
	(25, 1),
	(26, 1),
	(27, 1),
	(28, 1),
	(29, 1),
	(30, 1),
	(40, 1),
	(41, 1),
	(42, 1);

-- Dumping structure for table db_progress.schedule_management
CREATE TABLE IF NOT EXISTS `schedule_management` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.schedule_management: ~0 rows (approximately)

-- Dumping structure for table db_progress.sop
CREATE TABLE IF NOT EXISTS `sop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.sop: ~0 rows (approximately)

-- Dumping structure for table db_progress.sop_history
CREATE TABLE IF NOT EXISTS `sop_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(100) DEFAULT NULL,
  `sop_id` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.sop_history: ~0 rows (approximately)

-- Dumping structure for table db_progress.surat
CREATE TABLE IF NOT EXISTS `surat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `perihal` varchar(255) DEFAULT NULL,
  `jenis` varchar(200) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `nomor` varchar(255) DEFAULT NULL,
  `ext` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.surat: ~0 rows (approximately)

-- Dumping structure for view db_progress.surat_masuk
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `surat_masuk` (
	`id` INT(11) NOT NULL,
	`document_number` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`description` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`typeofincomingdocument` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`tanggal` DATETIME NULL,
	`from_` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`author` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`path` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`ext` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`created_at` DATETIME NULL,
	`updated_at` DATETIME NULL
) ENGINE=MyISAM;

-- Dumping structure for table db_progress.s_curve
CREATE TABLE IF NOT EXISTS `s_curve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `percent` float DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_progress.s_curve: ~0 rows (approximately)

-- Dumping structure for table db_progress.tasks
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `progress` double(8,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `parent` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `progress_target` double DEFAULT NULL,
  `parent_top` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.tasks: ~22 rows (approximately)
INSERT INTO `tasks` (`id`, `text`, `duration`, `progress`, `start_date`, `parent`, `created_at`, `updated_at`, `end_date`, `progress_target`, `parent_top`) VALUES
	(1, 'Project #1', 2872, 1.00, '2017-04-01 00:00:00', 0, NULL, '2025-02-09 19:46:20', '2025-02-10 00:00:00', NULL, 1),
	(2, 'Task #x', 310, 0.30, '2023-04-06 00:00:00', 7, NULL, '2025-02-09 19:47:11', '2024-02-10 00:00:00', NULL, 1),
	(3, 'Task #2', 2868, 0.30, '2017-04-05 00:00:00', 1, NULL, '2025-02-10 04:08:42', '2025-02-10 00:00:00', NULL, 1),
	(4, 'Task #3', 2866, 0.70, '2017-04-07 00:00:00', 1, NULL, '2025-02-09 19:47:36', '2025-02-10 00:00:00', NULL, 1),
	(5, 'Task #1.1', 5, 0.20, '2017-04-05 00:00:00', 2, NULL, NULL, NULL, NULL, 1),
	(6, 'Task #1.2', 4, 0.40, '2017-04-11 00:00:00', 2, NULL, NULL, NULL, NULL, 1),
	(7, 'Task #2.1', 5, 0.65, '2017-04-07 00:00:00', 3, NULL, NULL, NULL, NULL, 1),
	(8, 'Task #2.2', 4, 0.60, '2017-04-06 00:00:00', 3, NULL, NULL, NULL, NULL, 1),
	(10, 'tes', 23, 0.20, '2025-02-05 00:00:00', 0, '2025-02-09 18:39:03', '2025-02-09 18:39:03', '2025-02-28 00:00:00', NULL, 10),
	(11, 'yuhu', 21, 0.20, '2025-02-06 00:00:00', 0, '2025-02-09 18:41:18', '2025-02-09 18:41:18', '2025-02-27 00:00:00', NULL, 11),
	(12, 'klo', 23, 0.00, '2025-02-05 00:00:00', 0, '2025-02-09 18:49:44', '2025-02-09 18:49:44', '2025-02-28 00:00:00', NULL, 12),
	(13, 'kkkk', 22, 0.20, '2025-02-06 00:00:00', 1, '2025-02-09 18:52:02', '2025-02-09 18:52:02', '2025-02-28 00:00:00', NULL, 1),
	(14, 'person', 22, 0.30, '2025-02-01 00:00:00', 7, '2025-02-09 18:59:44', '2025-02-09 18:59:44', '2025-02-23 00:00:00', NULL, 1),
	(15, 'lll', 23, 0.07, '2025-02-05 00:00:00', 5, '2025-02-09 20:22:14', '2025-02-09 20:22:14', '2025-02-28 00:00:00', NULL, 1),
	(16, 'person 1', 23, 0.10, '2025-02-04 00:00:00', 14, '2025-02-10 16:35:06', '2025-02-10 16:35:06', '2025-02-27 00:00:00', NULL, 1),
	(17, 'yuhu1', 27, 0.20, '2025-02-01 00:00:00', 11, '2025-02-10 16:36:00', '2025-02-10 16:36:00', '2025-02-28 00:00:00', NULL, 11),
	(18, 'yuhu2', 27, 0.30, '2025-02-01 00:00:00', 17, '2025-02-10 16:36:20', '2025-02-10 16:36:20', '2025-02-28 00:00:00', NULL, 11),
	(19, 'yuhu3', 27, 0.05, '2025-02-01 00:00:00', 18, '2025-02-10 16:36:42', '2025-02-10 16:36:42', '2025-02-28 00:00:00', NULL, 11),
	(20, 'yuhu4', 26, 0.08, '2025-02-01 00:00:00', 19, '2025-02-10 16:37:00', '2025-02-10 16:37:00', '2025-02-27 00:00:00', NULL, 11),
	(22, 'gg', 25, 0.08, '2025-02-01 00:00:00', 0, '2025-02-10 16:45:38', '2025-02-10 16:45:38', '2025-02-26 00:00:00', NULL, 0),
	(23, 'UTAMA', 27, 0.07, '2025-02-01 00:00:00', 0, '2025-02-10 17:02:14', '2025-02-10 17:02:14', '2025-02-28 00:00:00', NULL, 23),
	(24, 'utama 1', 27, 0.06, '2025-02-01 00:00:00', 23, '2025-02-10 17:02:59', '2025-02-10 17:02:59', '2025-02-28 00:00:00', NULL, 23);

-- Dumping structure for table db_progress.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_progress.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `username`) VALUES
	(1, 'Super', 'superadmin@mail.com', NULL, '$2y$12$3DSQGM6ORZsjYRHXzuxscexKJR2fDrjQimPURlZyFfkmbPXqvYAeK', NULL, '2025-02-09 00:27:53', '2025-02-09 00:27:53', 'superadmin');

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `surat_masuk`;

;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
