-- Role Groups Table
CREATE TABLE IF NOT EXISTS `role_groups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Role-Group Mapping Table
CREATE TABLE IF NOT EXISTS `role_group_roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_group_id` INT NOT NULL,
    `role_id` INT NOT NULL,
    FOREIGN KEY (`role_group_id`) REFERENCES `role_groups`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
);

-- Branch Table Update: Add role_group_id
ALTER TABLE `branch` ADD COLUMN `role_group_id` INT DEFAULT NULL;
ALTER TABLE `branch` ADD FOREIGN KEY (`role_group_id`) REFERENCES `role_groups`(`id`) ON DELETE SET NULL;
