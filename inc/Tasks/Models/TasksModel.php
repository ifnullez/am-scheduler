<?php
namespace AM\Scheduler\Tasks\Models;

use AM\Scheduler\Base\Abstractions\BaseModel;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Entities\Entity\TasksEntity;

class TasksModel extends BaseModel
{
    use Singleton;

    private function __construct()
    {
        $this->table_name = TasksEntity::getInstance()->table_name;
        $this->wpdb = TasksEntity::getInstance()->wpdb;
    }
}
