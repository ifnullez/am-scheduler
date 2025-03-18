<?php

namespace MHS\Rrule\Interfaces;

interface RruleActionInterface
{
    public function onCall(array $series = []): void;
}
