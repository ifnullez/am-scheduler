<?php

namespace AM\Scheduler\Rrule\Abstractions;

abstract class RruleActionAbstraction
{
    protected ?string $action = "";

    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
