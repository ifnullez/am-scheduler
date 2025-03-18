<?php
namespace MHS\Series\Models;

use MHS\Base\Abstractions\BaseModel;
use MHS\Base\Enums\EventsSeriesStatuses;
use MHS\Base\Traits\Singleton;
use MHS\Entities\Entity\SeriesEntity;
use MHS\Rrule\Controllers\RruleStringGenerator;

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
