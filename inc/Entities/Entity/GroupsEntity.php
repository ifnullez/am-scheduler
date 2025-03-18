<?php

namespace MHS\Entities\Entity;

use MHS\Entities\Abstractions\AbstractEntity;
use MHS\Entities\Interfaces\EntityInterface;
use MHS\Entities\Traits\EntityTrait;

class GroupsEntity extends AbstractEntity implements EntityInterface
{
    use EntityTrait;

    protected ?string $table_name = "posts";

    public function schema(): string
    {
        return "";
    }

    public function updateSchema(): ?array
    {
        return null;
    }
}
