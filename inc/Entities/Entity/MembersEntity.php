<?php
namespace MHS\Entities\Entity;

use MHS\Entities\Abstractions\AbstractEntity;
use MHS\Entities\Interfaces\EntityInterface;
use MHS\Entities\Traits\EntityTrait;

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
