<?php
namespace AM\Scheduler\Series\Models;

use AM\Scheduler\Base\Abstractions\BaseModel;
use AM\Scheduler\Base\Enums\EventsSeriesStatuses;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Entities\Entity\SeriesEntity;
use AM\Scheduler\Rrule\Controllers\RruleStringGenerator;

class SeriesModel extends BaseModel
{
    use Singleton;

    private function __construct()
    {
        $this->table_name = SeriesEntity::getInstance()->table_name;
        $this->wpdb = SeriesEntity::getInstance()->wpdb;
    }

    public function getForExecute(string $modify = "+0 days"): ?array
    {
        $new = EventsSeriesStatuses::NEW->value;
        $failed = EventsSeriesStatuses::FAILED->value;

        $current_date_time = (new RruleStringGenerator())->createValidDate(
            modify: $modify
        );
        return $this->wpdb->get_results(
            "SELECT * FROM {$this->wpdb->prefix}{$this->table_name} WHERE `starting_at` <= '{$current_date_time}' AND (`execution_status` = '{$new}' OR `execution_status` = '{$failed}')",
            ARRAY_A
        );
    }
}
