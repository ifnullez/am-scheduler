<?php

namespace AM\Scheduler\Entities\Entity;

use AM\Scheduler\Base\Helpers\StaticHelper;
use AM\Scheduler\Entities\Abstractions\AbstractEntity;
use AM\Scheduler\Entities\Interfaces\EntityInterface;
use AM\Scheduler\Entities\Traits\EntityTrait;

class EventsEntity extends AbstractEntity implements EntityInterface
{
    use EntityTrait;

    protected ?string $table_name = "mh_scheduler_events";
    protected ?array $indexes = ["task_id", "author_id", "id"];

    protected ?array $constraints = ["mh_scheduler_event_task_id"];

    public function schema(): string
    {
        $charset = $this->wpdb->get_charset_collate();

        return "CREATE TABLE IF NOT EXISTS `{$this->wpdb->prefix}{$this->table_name}` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `author_id` BIGINT NOT NULL,
            `task_id` BIGINT UNSIGNED,
            `title` VARCHAR(255) NOT NULL,
            `description` VARCHAR(255) NULL,
            `view_status` VARCHAR(255) NOT NULL DEFAULT 'publish',
            `execution_status` VARCHAR(255) NOT NULL DEFAULT 'created',
            `meta` JSON NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `pattern` VARCHAR(255) NULL,
            `start_date` VARCHAR(255) NULL,
            `until` VARCHAR(255) NULL,
            `freq` VARCHAR(255) NULL,
            `count` VARCHAR(255) NULL,
            `interval` VARCHAR(255) NULL,
            `adjust_month_end` VARCHAR(255) NULL,
            `by_day` VARCHAR(255) NULL,
            `by_month_day` VARCHAR(255) NULL,
            `by_year_day` VARCHAR(255) NULL,
            `by_week_no` VARCHAR(255) NULL,
            `by_month` VARCHAR(255) NULL,
            `by_set_pos` VARCHAR(255) NULL,
            `by_hour` VARCHAR(255) NULL,
            `by_minute` VARCHAR(255) NULL,
            `by_second` VARCHAR(255) NULL
        ) {$charset}";
    }

    public function updateSchema(): ?array
    {
        $scheduler_task_table_name = TasksEntity::getInstance()->table_name;
        $indexes_string = StaticHelper::createRequestStringForUpdate(
            $this->indexes,
            ", ",
            "`"
        );

        $schema = [
            "indexes" => [
                "ALTER TABLE `{$this->wpdb->prefix}{$this->table_name}`
                    ADD INDEX ($indexes_string);",
            ],
            "constraints" => [
                "mh_scheduler_event_task_id" => "ALTER TABLE `{$this->wpdb->prefix}{$this->table_name}`
                    ADD CONSTRAINT `mh_scheduler_event_task_id`
                    FOREIGN KEY(`task_id`)
                    REFERENCES `{$this->wpdb->prefix}{$scheduler_task_table_name}`(`id`); ",
            ],
        ];

        return $schema;
    }
}
