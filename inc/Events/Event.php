<?php

namespace AM\Scheduler\Events;

use AM\Scheduler\Base\Abstractions\BaseObject;
use AM\Scheduler\Events\Interfaces\EventInterface;
use AM\Scheduler\Events\Models\EventsModel;

class Event extends BaseObject implements EventInterface
{
    protected ?int $id = null;
    protected ?int $author_id = null;
    protected ?string $title = null;
    protected ?string $message = null;
    protected ?string $reason = null;
    protected ?int $task_id = null;
    protected ?int $group_id = null;
    protected ?string $pattern = null;
    protected ?string $updated_at = null;
    protected ?string $created_at = null;
    protected ?array $meta = [];
    protected ?int $is_recurring = 0;
    protected ?string $view_status = null;
    protected ?string $execution_status = null;
    protected ?string $description = null;
    protected ?string $start_date = null;
    protected ?string $until = null;
    protected ?string $freq = null;
    protected ?string $count = null;
    protected ?string $interval = null;
    protected ?string $adjust_month_end = null;
    protected ?string $by_day = null;
    protected ?string $by_month_day = null;
    protected ?string $by_year_day = null;
    protected ?string $by_week_no = null;
    protected ?string $by_month = null;
    protected ?string $by_set_pos = null;
    protected ?string $by_hour = null;
    protected ?string $by_minute = null;
    protected ?string $by_second = null;

    public function __construct(int $id)
    {
        $this->populateProperties(
            EventsModel::getInstance()->findOne("id", $id)
        );
    }
}
