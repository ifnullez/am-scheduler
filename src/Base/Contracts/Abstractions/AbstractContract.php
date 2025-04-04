<?php

namespace AM\Scheduler\Base\Contracts\Abstractions;

abstract class AbstractContract
{
    protected ?string $table_name = null;
    protected ?array $indexes = [];
    protected ?array $constraints = [];

    public function __toString(): string
    {
        return $this::class;
    }

    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
