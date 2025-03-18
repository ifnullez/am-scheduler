<?php

namespace MHS\Groups\Models;

use MHS\Base\Abstractions\BaseModel;
use MHS\Base\Traits\Singleton;
use MHS\Entities\Entity\GroupsEntity;
use MHS\Entities\Entity\SeriesEntity;

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

    public function getSeriesBetweenDates(int $group_id, string $start_date, string $end_date): ?array
    {
        return $this->wpdb->get_results(
            "SELECT * FROM {$this->wpdb->prefix}{$this->series_entity->table_name}
            WHERE `group_id` = '{$group_id}'
            AND (`starting_at` >= '{$start_date}' AND `starting_at` <= '{$end_date}')",
            ARRAY_A
        );
    }
}
