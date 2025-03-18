<?php
namespace MHS\Tasks\Models;

use MHS\Base\Abstractions\BaseModel;
use MHS\Base\Traits\Singleton;
use MHS\Entities\Entity\TasksEntity;

class TasksModel extends BaseModel
{
    use Singleton;

    private function __construct()
    {
        $this->table_name = TasksEntity::getInstance()->table_name;
        $this->wpdb = TasksEntity::getInstance()->wpdb;
    }
}
