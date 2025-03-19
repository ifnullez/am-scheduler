<?php
namespace AM\Scheduler\Members\Models;

use AM\Scheduler\Base\Abstractions\BaseModel;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Entities\Entity\MembersEntity;

class MembersModel extends BaseModel
{
    use Singleton;

    private function __construct()
    {
        $this->table_name = MembersEntity::getInstance()->table_name;
        $this->wpdb = MembersEntity::getInstance()->wpdb;
    }
}
