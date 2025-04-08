<?php

namespace AM\Scheduler\Base\Contracts\Abstractions;

use AM\Scheduler\Base\Traits\GetterTrait;

abstract class AbstractContract
{
    use GetterTrait;

    protected ?string $table_name = null;
    protected ?array $indexes = [];
    protected ?array $constraints = [];

    public function __toString(): string
    {
        return $this::class;
    }
}
