<?php

namespace AM\Scheduler\Base\Entities;

use AM\Scheduler\Base\Traits\GetterTrait;
use AM\Scheduler\Base\Traits\SetterTrait;

abstract class AbstractEntity
{
    use BaseEntityTrait, GetterTrait, SetterTrait;

    public function toArray(): ?array
    {
        return get_object_vars($this);
    }

    /**
     * Get a unique identifier for the entity.
     *
     * @return string
     */
    abstract public function getIdentifier(): string;
}
