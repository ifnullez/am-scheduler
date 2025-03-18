<?php

namespace MHS\Series\Interfaces;

interface SeriesInterface
{
    public function __get(string $property): mixed;
}
