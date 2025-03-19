<?php

namespace AM\Scheduler\Tasks;

use AM\Scheduler\Base\Abstractions\BaseObject;
use AM\Scheduler\Tasks\Interfaces\TaskInterface;
use AM\Scheduler\Tasks\Models\TasksModel;

class Task extends BaseObject implements TaskInterface
{
    protected ?int $id = null;
    protected ?int $author_id = null;
    protected ?int $amount = null;
    protected ?int $event_id = null;
    protected ?int $group_id = null;
    protected ?string $members_ids = null;
    protected ?string $action = null;
    protected ?string $title = null;
    protected ?string $message = null;
    protected ?string $reason = null;
    protected ?string $description = null;
    protected ?string $created_at = null;
    protected ?string $updated_at = null;
    protected ?array $meta = [];

    public function __construct(int $id)
    {
        $this->populateProperties(
            TasksModel::getInstance()->findOne("id", $id)
        );
    }
}
