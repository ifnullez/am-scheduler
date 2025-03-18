<?php

namespace MHS\Rrule\Abstractions;

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
