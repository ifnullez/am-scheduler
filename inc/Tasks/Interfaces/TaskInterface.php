<?php

namespace MHS\Tasks\Interfaces;

interface TaskInterface
{
    public function __get(string $property): mixed;
}
