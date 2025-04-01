-- First, create a backup table if it doesn't exist
CREATE TABLE IF NOT EXISTS user_settings_backup (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `workspace_id` bigint(20) UNSIGNED NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Backup existing data if the backup table is empty
INSERT INTO user_settings_backup 
SELECT * FROM user_settings
WHERE (SELECT COUNT(*) FROM user_settings_backup) = 0;

-- Drop any foreign keys that reference user_settings
SET @database = DATABASE();
SET group_concat_max_len = 8192;

SET @sql = (
    SELECT GROUP_CONCAT(
        CONCAT('ALTER TABLE `', table_name, '` DROP FOREIGN KEY `', constraint_name, '`;')
    )
    FROM information_schema.referential_constraints
    WHERE referenced_table_name = 'user_settings'
    AND constraint_schema = @database
);

SET @sql = IFNULL(@sql, 'SELECT "No foreign keys found"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Now drop and recreate the table with the correct schema
DROP TABLE IF EXISTS user_settings;

CREATE TABLE `user_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `workspace_id` bigint(20) UNSIGNED NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_settings_key_index` (`key`),
  KEY `user_settings_user_id_foreign` (`user_id`),
  KEY `user_settings_workspace_id_foreign` (`workspace_id`),
  UNIQUE KEY `user_settings_user_workspace_key_unique` (`user_id`, `workspace_id`, `key`),
  CONSTRAINT `user_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_settings_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Restore data from backup (make sure to handle the case if workspace_id is missing in backup)
INSERT INTO user_settings
SELECT * FROM user_settings_backup
WHERE workspace_id IS NOT NULL;

-- Insert records with null workspace_id to prevent issues with the unique constraint
INSERT INTO user_settings (id, user_id, workspace_id, `key`, value, created_at, updated_at)
SELECT id, user_id, NULL, `key`, value, created_at, updated_at 
FROM user_settings_backup
WHERE workspace_id IS NULL; 