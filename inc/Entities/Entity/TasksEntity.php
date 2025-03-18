<?php

namespace MHS\Entities\Entity;

use MHS\Base\Helpers\StaticHelper;
use MHS\Entities\Abstractions\AbstractEntity;
use MHS\Entities\Interfaces\EntityInterface;
use MHS\Entities\Traits\EntityTrait;
use MHS\Entities\Entity\EventsEntity;

class TasksEntity extends AbstractEntity implements EntityInterface
{
    use EntityTrait;

    protected ?string $table_name = "mh_scheduler_events_tasks";
    protected ?array $indexes = ["action", "group_id", "author_id"];

    protected ?array $constraints = ["mh_scheduler_tasks_event_id"];

    public function schema(): string
    {
        $charset = $this->wpdb->get_charset_collate();

        return "CREATE TABLE IF NOT EXISTS `{$this->wpdb->prefix}{$this->table_name}` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `action` VARCHAR(255) NULL DEFAULT NULL,
            `author_id` BIGINT NOT NULL,
            `group_id` BIGINT NULL,
            `amount` BIGINT NULL,
            `members_ids` JSON NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `description` VARCHAR(255) NULL,
            `message` VARCHAR(255) NOT NULL,
            `reason` VARCHAR(255) NOT NULL,
            `view_status` VARCHAR(255) NOT NULL DEFAULT 'publish',
            `meta` JSON NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) {$charset}";
    }

    public function updateSchema(): ?array
    {
        // $events_table_slug = EventsEntity::getInstance()->table_name;
        $indexes_string = StaticHelper::createRequestStringForUpdate(
            $this->indexes,
            ", ",
            "`"
        );

        $schema = [
            "indexes" => [
                "ALTER TABLE
                    `{$this->wpdb->prefix}{$this->table_name}` ADD INDEX (
                        $indexes_string
                )",
            ],
            // "constraints" => [
            //     "mh_scheduler_tasks_event_id" => "ALTER TABLE `{$this->wpdb->prefix}{$this->table_name}`
            //     ADD CONSTRAINT `mh_scheduler_tasks_event_id` FOREIGN KEY(`event_id`)
            //     REFERENCES `{$this->wpdb->prefix}{$events_table_slug}`(`id`)",
            // ],
        ];

        return $schema ?? "";
    }
}
