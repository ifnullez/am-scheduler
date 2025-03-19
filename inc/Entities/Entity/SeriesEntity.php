<?php

namespace AM\Scheduler\Entities\Entity;

use AM\Scheduler\Base\Enums\EventsSeriesStatuses;
use AM\Scheduler\Base\Helpers\StaticHelper;
use AM\Scheduler\Entities\Abstractions\AbstractEntity;
use AM\Scheduler\Entities\Interfaces\EntityInterface;
use AM\Scheduler\Entities\Traits\EntityTrait;
use AM\Scheduler\Entities\Entity\EventsEntity;

class SeriesEntity extends AbstractEntity implements EntityInterface
{
    use EntityTrait;

    protected ?string $table_name = "mh_scheduler_events_series";
    protected ?array $indexes = ["task_id", "group_id", "execution_status"];

    protected ?array $constraints = [
        "mh_scheduler_series_task_id",
        "mh_scheduler_series_event_id",
    ];

    public function schema(): string
    {
        $charset = $this->wpdb->get_charset_collate();

        $series_status = EventsSeriesStatuses::NEW->value;

        return "CREATE TABLE IF NOT EXISTS `{$this->wpdb->prefix}{$this->table_name}` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `event_id` BIGINT UNSIGNED,
            `task_id` BIGINT UNSIGNED,
            `action` VARCHAR(255) NULL DEFAULT NULL,
            `amount` BIGINT NULL,
            `author_id` BIGINT NULL DEFAULT NULL,
            `group_id` BIGINT NULL DEFAULT NULL,
            `members_ids` JSON NOT NULL,
            `message` VARCHAR(255) NOT NULL,
            `reason` VARCHAR(255) NOT NULL,
            `meta` JSON NULL,
            `starting_at` DATETIME NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `executed_at` DATETIME NULL DEFAULT NULL,
            `execution_status` VARCHAR(255) NOT NULL DEFAULT '{$series_status}'
        ) {$charset}";
    }

    public function updateSchema(): ?array
    {
        $events_table_slug = EventsEntity::getInstance()->table_name;
        $events_tasks_table_slug = TasksEntity::getInstance()->table_name;
        $indexes_string = StaticHelper::createRequestStringForUpdate(
            $this->indexes,
            ", ",
            "`"
        );

        $schema = [
            "indexes" => [
                "ALTER TABLE `{$this->wpdb->prefix}{$this->table_name}` ADD INDEX (
                    $indexes_string
                )",
            ],
            "constraints" => [
                "mh_scheduler_series_task_id" => "
            ALTER TABLE `{$this->wpdb->prefix}{$this->table_name}`
            ADD CONSTRAINT `mh_scheduler_series_task_id`
            FOREIGN KEY(`task_id`)
            REFERENCES `{$this->wpdb->prefix}{$events_tasks_table_slug}`(`id`)",
                "mh_scheduler_series_event_id" => "
                ALTER TABLE `{$this->wpdb->prefix}{$this->table_name}`
                ADD CONSTRAINT `mh_scheduler_series_event_id` FOREIGN KEY(`event_id`)
                REFERENCES `{$this->wpdb->prefix}{$events_table_slug}`(`id`)
                ON DELETE CASCADE ON UPDATE CASCADE",
            ],
        ];

        return $schema;
    }
}
