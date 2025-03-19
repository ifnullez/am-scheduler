<?php
namespace AM\Scheduler\Events\Models;

use AM\Scheduler\Base\Abstractions\BaseModel;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Entities\Entity\EventsEntity;

class EventsModel extends BaseModel
{
    use Singleton;

    private function __construct()
    {
        $this->table_name = EventsEntity::getInstance()->table_name;
        $this->wpdb = EventsEntity::getInstance()->wpdb;
    }
}
