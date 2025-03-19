<?php

namespace AM\Scheduler\Entities\Abstractions;

use wpdb;

abstract class AbstractEntity
{
    protected ?string $table_name = null;
    protected ?wpdb $wpdb = null;
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
