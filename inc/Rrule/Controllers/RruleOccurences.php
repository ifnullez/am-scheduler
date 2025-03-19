<?php

namespace AM\Scheduler\Rrule\Controllers;

use When\When;
use AM\Scheduler\Rrule\Controllers\RruleStringGenerator;

class RruleOccurences
{
    protected ?array $data;
    protected ?When $rrule;
    protected ?string $pattern;
    protected ?string $range_limit;

    public function __construct(array $data, ?int $range_limit = 366)
    {
        $this->data = $data;
        $this->pattern = !empty($this->data)
            ? (new RruleStringGenerator($this->data))->getRruleParamsString()
            : null;
        $this->range_limit = $range_limit;
        $this->rrule = $this->whenObject();
    }

    private function whenObject(): ?When
    {
        $r = new When();

        if (!empty($this->pattern)) {
            $r->rangeLimit = $this->range_limit;
            $r->RFC5545_COMPLIANT = When::IGNORE;
            $r->rrule($this->pattern)->generateOccurrences();
        }

        return $r;
    }

    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
