<?php
namespace MHS\Members\Models;

use MHS\Base\Abstractions\BaseModel;
use MHS\Base\Traits\Singleton;
use MHS\Entities\Entity\MembersEntity;

class MembersModel extends BaseModel
{
    use Singleton;

    private function __construct()
    {
        $this->table_name = MembersEntity::getInstance()->table_name;
        $this->wpdb = MembersEntity::getInstance()->wpdb;
    }
}
