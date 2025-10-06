/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `analysis_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analysis_results` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sample_rawmat_id` bigint unsigned NOT NULL,
  `specification_id` bigint unsigned NOT NULL,
  `result_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','completed','passed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tested_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tested_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `analysis_results_sample_rawmat_id_specification_id_unique` (`sample_rawmat_id`,`specification_id`),
  KEY `analysis_results_specification_id_foreign` (`specification_id`),
  CONSTRAINT `analysis_results_sample_rawmat_id_foreign` FOREIGN KEY (`sample_rawmat_id`) REFERENCES `sample_rawmats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `analysis_results_specification_id_foreign` FOREIGN KEY (`specification_id`) REFERENCES `specifications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `instrument_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instrument_conditions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instrument_id` bigint unsigned NOT NULL,
  `shift` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operator_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condition` enum('good','damaged') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `time` time NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `instrument_conditions_instrument_id_foreign` (`instrument_id`),
  CONSTRAINT `instrument_conditions_instrument_id_foreign` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `instruments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instruments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `raw_mat_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `raw_mat_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `raw_material_samples`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `raw_material_samples` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `raw_mat_id` bigint unsigned NOT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_lot` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_container_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_coa` tinyint(1) NOT NULL DEFAULT '0',
  `coa_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submission_time` datetime NOT NULL,
  `entry_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `submitted_by` bigint unsigned NOT NULL,
  `status` enum('pending','in_progress','analysis_completed','reviewed','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `analysis_method` enum('individual','joint') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `analysis_results` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `primary_analyst_id` bigint unsigned DEFAULT NULL,
  `secondary_analyst_id` bigint unsigned DEFAULT NULL,
  `analysis_started_at` datetime DEFAULT NULL,
  `analysis_completed_at` datetime DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `raw_material_samples_category_id_foreign` (`category_id`),
  KEY `raw_material_samples_raw_mat_id_foreign` (`raw_mat_id`),
  KEY `raw_material_samples_submitted_by_foreign` (`submitted_by`),
  KEY `raw_material_samples_reference_id_foreign` (`reference_id`),
  KEY `raw_material_samples_primary_analyst_id_foreign` (`primary_analyst_id`),
  KEY `raw_material_samples_secondary_analyst_id_foreign` (`secondary_analyst_id`),
  KEY `raw_material_samples_reviewed_by_foreign` (`reviewed_by`),
  KEY `raw_material_samples_approved_by_foreign` (`approved_by`),
  CONSTRAINT `raw_material_samples_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `raw_material_samples_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `raw_mat_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `raw_material_samples_primary_analyst_id_foreign` FOREIGN KEY (`primary_analyst_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `raw_material_samples_raw_mat_id_foreign` FOREIGN KEY (`raw_mat_id`) REFERENCES `raw_mats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `raw_material_samples_reference_id_foreign` FOREIGN KEY (`reference_id`) REFERENCES `references` (`id`) ON DELETE SET NULL,
  CONSTRAINT `raw_material_samples_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `raw_material_samples_secondary_analyst_id_foreign` FOREIGN KEY (`secondary_analyst_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `raw_material_samples_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `raw_mats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `raw_mats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `raw_mats_category_id_foreign` (`category_id`),
  CONSTRAINT `raw_mats_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `raw_mat_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reference_specification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reference_specification` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference_id` bigint unsigned NOT NULL,
  `specification_id` bigint unsigned NOT NULL,
  `operator` enum('>=','<=','==','-','should_be','range') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '==',
  `value` text COLLATE utf8mb4_unicode_ci,
  `max_value` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference_specification_reference_id_specification_id_unique` (`reference_id`,`specification_id`),
  KEY `reference_specification_specification_id_foreign` (`specification_id`),
  CONSTRAINT `reference_specification_reference_id_foreign` FOREIGN KEY (`reference_id`) REFERENCES `references` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reference_specification_specification_id_foreign` FOREIGN KEY (`specification_id`) REFERENCES `specifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `references`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `references` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rawmat_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `references_rawmat_id_foreign` (`rawmat_id`),
  CONSTRAINT `references_rawmat_id_foreign` FOREIGN KEY (`rawmat_id`) REFERENCES `raw_mats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `permissions` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sample_chemicals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sample_chemicals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `chemical_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chemical_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `concentration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cas_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `submission_date` date NOT NULL,
  `submission_time` time NOT NULL,
  `has_coa` tinyint(1) NOT NULL DEFAULT '0',
  `coa_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitter_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Current User',
  `status` enum('submission','analysis_process','analysis_completed','review','result','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submission',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sample_chemicals_category_id_foreign` (`category_id`),
  CONSTRAINT `sample_chemicals_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `raw_mat_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sample_rawmats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sample_rawmats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `rawmat_id` bigint unsigned NOT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `car_container_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `submission_date` date NOT NULL,
  `submission_time` time NOT NULL,
  `has_coa` tinyint(1) NOT NULL DEFAULT '0',
  `coa_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitter_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'System User',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('submission','analysis_process','analysis_completed','review_results','approve') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submission',
  `analysis_start_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sample_rawmats_category_id_foreign` (`category_id`),
  KEY `sample_rawmats_rawmat_id_foreign` (`rawmat_id`),
  KEY `sample_rawmats_reference_id_foreign` (`reference_id`),
  CONSTRAINT `sample_rawmats_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `raw_mat_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sample_rawmats_rawmat_id_foreign` FOREIGN KEY (`rawmat_id`) REFERENCES `raw_mats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sample_rawmats_reference_id_foreign` FOREIGN KEY (`reference_id`) REFERENCES `references` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sample_solders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sample_solders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `solder_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `composition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `submission_date` date NOT NULL,
  `submission_time` time NOT NULL,
  `has_coa` tinyint(1) NOT NULL DEFAULT '0',
  `coa_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitter_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Current User',
  `status` enum('submission','analysis_process','analysis_completed','review','result','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submission',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sample_solders_category_id_foreign` (`category_id`),
  CONSTRAINT `sample_solders_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `raw_mat_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `specifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `specifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `spesification_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `spesification_value` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `thermohygrometer_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `thermohygrometer_conditions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `thermohygrometer_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shift` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operator_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condition` enum('good','damaged') COLLATE utf8mb4_unicode_ci NOT NULL,
  `temperature` decimal(5,2) DEFAULT NULL,
  `humidity` decimal(5,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `time` time NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `thermohygrometer_conditions_thermohygrometer_id_index` (`thermohygrometer_id`),
  CONSTRAINT `thermohygrometer_conditions_thermohygrometer_id_foreign` FOREIGN KEY (`thermohygrometer_id`) REFERENCES `thermohygrometers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `thermohygrometers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `thermohygrometers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint unsigned DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_07_14_034850_create_raw_mat_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_07_14_034907_create_raw_mats_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_07_14_034918_create_references_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_07_14_034930_create_specifications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_07_14_043218_create_reference_specification_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_07_28_041146_create_spesification_value',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_07_28_075402_modify_reference_specification_value_column',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_07_28_094652_add_string_operators_to_reference_specification',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_07_29_023815_add_range_operator_to_reference_specification',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_07_29_024611_remove_contains_operator_from_reference_specification',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_07_29_082336_create_sample_rawmats_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_07_29_083423_update_sample_rawmats_status_enum',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_07_30_160303_create_analysis_results_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_08_01_071222_create_sample_solders_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_08_01_071236_create_sample_chemicals_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_08_01_084247_add_reference_id_to_sample_rawmats_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_08_01_174023_add_analysis_start_time_to_sample_rawmats_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_08_15_040935_add_two_factor_columns_to_users_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_08_18_132150_add_role_to_users_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_08_18_141514_create_roles_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_08_18_141538_update_users_table_for_roles',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_08_18_142848_migrate_user_roles_data',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_08_18_145428_create_raw_material_samples_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_08_19_034559_add_coa_file_path_to_raw_material_samples_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_08_21_045238_create_instrument_conditions_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_08_21_100410_add_operator_name_to_instrument_conditions_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_08_22_043609_create_instruments_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_08_22_050932_add_instrument_data_to_instrument_conditions_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_08_22_060845_create_thermohygrometers_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_08_22_075235_create_thermohygrometer_conditions_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_08_23_194815_update_thermohygrometer_conditions_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_08_23_205322_add_temperature_humidity_to_thermohygrometer_conditions_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_08_24_153353_add_reference_id_to_raw_material_samples_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_08_24_213133_add_analysis_fields_to_raw_material_samples_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_08_25_044052_drop_analysis_notes_from_raw_material_samples_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_08_25_214425_add_analysis_results_to_raw_material_samples_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_08_25_220257_add_review_and_approval_timestamps_to_raw_material_samples_table',19);
