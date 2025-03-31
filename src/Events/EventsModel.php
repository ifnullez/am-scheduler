<?php
namespace AM\Scheduler\Events;

use AM\Scheduler\Base\Entities\AbstractEntity;
use AM\Scheduler\Base\Models\AbstractBaseModel;
use AM\Scheduler\Events\Event;

class EventsModel extends AbstractBaseModel
{
    public function __construct()
    {
        parent::__construct("events");
    }

    protected function createEntity(array $data): AbstractEntity
    {
        return new Event($data);
    }

    /**
     * Find upcoming events.
     *
     * @param int|null $limit Number of events to return
     * @return array<int, Event>
     */
    public function findUpcoming(?int $limit = null): array
    {
        $builder = $this->queryBuilder
            ->select($this->tableName, ["*"])
            ->where("event_date", current_time("mysql"), ">=");

        if ($limit !== null) {
            $builder->limit(0, $limit);
        }

        $sql = $builder->getSQL();
        $results = $this->wpdb->get_results($sql, ARRAY_A) ?? [];
        return array_map([$this, "createEntity"], $results);
    }
}
