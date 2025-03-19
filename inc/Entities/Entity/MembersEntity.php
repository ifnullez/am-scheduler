<?php
namespace AM\Scheduler\Entities\Entity;

use AM\Scheduler\Entities\Abstractions\AbstractEntity;
use AM\Scheduler\Entities\Interfaces\EntityInterface;
use AM\Scheduler\Entities\Traits\EntityTrait;

class MembersEntity extends AbstractEntity implements EntityInterface
{
    use EntityTrait;

    protected ?string $table_name = "users";

    public function schema(): string
    {
        return "";
    }

    public function updateSchema(): ?array
    {
        return null;
    }
}
