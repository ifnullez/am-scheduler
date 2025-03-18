<?php

namespace MHS\Events\Interfaces;

interface EventInterface
{
    public function __get(string $property): mixed;
}
