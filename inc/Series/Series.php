<?php

namespace AM\Scheduler\Series;

use AM\Scheduler\Base\Abstractions\BaseObject;
use AM\Scheduler\Series\Models\SeriesModel;
use AM\Scheduler\Series\Interfaces\SeriesInterface;

class Series extends BaseObject implements SeriesInterface
{
    protected ?int $id = null;
    protected ?int $event_id = null;
    protected ?int $author_id = null;
    protected ?int $task_id = null;
    protected ?int $group_id = null;
    protected ?int $amount = null;
    protected ?array $meta = [];
    protected ?string $members_ids = null;
    protected ?string $title = null;
    protected ?string $message = null;
    protected ?string $reason = null;
    protected ?string $starting_at = null;
    protected ?string $executed_at = null;
    protected ?string $created_at = null;
    protected ?string $updated_at = null;
    protected ?string $execution_status = null;
    protected ?string $action = null;

    public function __construct(int $id)
    {
        $this->populateProperties(
            SeriesModel::getInstance()->findOne("id", $id)
        );
    }
}
