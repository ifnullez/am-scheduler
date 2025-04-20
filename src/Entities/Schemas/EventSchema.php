<?php

namespace AM\Scheduler\Entities\Schemas;

use AM\Scheduler\Base\Contracts\Abstractions\AbstractContract;
use AM\Scheduler\Base\Contracts\Interfaces\ContractInterface;
use AM\Scheduler\Base\Contracts\Traits\ContractSchemaTrait;

class EventSchema extends AbstractContract implements ContractInterface
{
    use ContractSchemaTrait;

    protected ?string $table_name = "events";

    public function schema(): ?string
    {
        return $this->queryBuilder
            ->create(
                "{$this->wpdb->prefix}{$this->plugin_slug}_{$this->table_name}",
                ["PRIMARY KEY (`id`)"],
                true
            )
            ->addColumn("id", "BIGINT UNSIGNED", [
                "nullable" => false,
                "auto_increment" => true,
            ])
            ->addColumn("title", "MEDIUMTEXT", ["nullable" => false])
            ->addColumn("description", "LONGTEXT", ["nullable" => true])
            ->addColumn("short_description", "MEDIUMTEXT", ["nullable" => true])
            ->addColumn("task_id", "BIGINT", ["nullable" => false])
            ->addColumn("rrule", "LONGTEXT", ["nullable" => false])
            ->addColumn("created_at", "DATETIME", [
                "nullable" => false,
                "default" => "CURRENT_TIMESTAMP",
            ])
            ->addColumn("updated_at", "DATETIME", [
                "nullable" => false,
                "default" => "CURRENT_TIMESTAMP",
            ])
            ->getSQL();
    }
    public function update(): ?array
    {
        return null;
    }
}
