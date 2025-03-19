<?php

namespace AM\Scheduler\Groups\Models;

use AM\Scheduler\Base\Abstractions\BaseModel;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Entities\Entity\GroupsEntity;
use AM\Scheduler\Entities\Entity\SeriesEntity;

class GroupsModel extends BaseModel
{
    use Singleton;

    private SeriesEntity $series_entity;

    private function __construct()
    {
        $this->table_name = GroupsEntity::getInstance()->table_name;
        $this->wpdb = GroupsEntity::getInstance()->wpdb;
        $this->series_entity = SeriesEntity::getInstance();
    }

    public function getSeriesBetweenDates(
        int $group_id,
        string $start_date,
        string $end_date
    ): ?array {
        return $this->wpdb->get_results(
            "SELECT * FROM {$this->wpdb->prefix}{$this->series_entity->table_name}
            WHERE `group_id` = '{$group_id}'
            AND (`starting_at` >= '{$start_date}' AND `starting_at` <= '{$end_date}')",
            ARRAY_A
        );
    }
}
