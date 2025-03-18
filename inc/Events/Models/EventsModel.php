<?php
namespace MHS\Events\Models;

use MHS\Base\Abstractions\BaseModel;
use MHS\Base\Traits\Singleton;
use MHS\Entities\Entity\EventsEntity;

class EventsModel extends BaseModel
{
    use Singleton;

    private function __construct()
    {
        $this->table_name = EventsEntity::getInstance()->table_name;
        $this->wpdb = EventsEntity::getInstance()->wpdb;
    }
}
