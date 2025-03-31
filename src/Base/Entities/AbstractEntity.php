<?php

namespace AM\Scheduler\Base\Entities;

abstract class AbstractEntity
{
    use BaseEntityTrait;

    private $data = [];

    public function __get(mixed $name): mixed
    {
        return $this->data[$name] ?? null;
    }

    public function __set(mixed $name, mixed $value): void
    {
        $this->data[$name] = $value;
    }

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
