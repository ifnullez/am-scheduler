<?php

namespace AM\Scheduler\Rrule\Interfaces;

interface RruleActionInterface
{
    public function onCall(array $series = []): void;
}
