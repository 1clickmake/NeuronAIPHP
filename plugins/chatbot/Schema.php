<?php

namespace Plugins\chatbot;

use App\Core\Database;

class Schema
{
    public function install()
    {
        $db = Database::getInstance();
        
        // Add columns to ai_config table if they don't exist
        try { $db->exec("ALTER TABLE `ai_config` ADD COLUMN `use_chatbot` TINYINT(1) DEFAULT 0 AFTER `default_model` "); } catch (\Exception $e) { }
        try { $db->exec("ALTER TABLE `ai_config` ADD COLUMN `chatbot_limit_guest` INT DEFAULT 5 AFTER `use_chatbot` "); } catch (\Exception $e) { }
        try { $db->exec("ALTER TABLE `ai_config` ADD COLUMN `chatbot_limit_member` INT DEFAULT 20 AFTER `chatbot_limit_guest` "); } catch (\Exception $e) { }

        // Create chatbot_logs table
        $sql = "CREATE TABLE IF NOT EXISTS `chatbot_logs` (
            `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `session_id` VARCHAR(100) NOT NULL,
            `user_id` VARCHAR(50) DEFAULT NULL,
            `question` TEXT NOT NULL,
            `answer` TEXT NOT NULL,
            `ip_address` VARCHAR(45) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX (`session_id`),
            INDEX (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $db->exec($sql);
    }
}
