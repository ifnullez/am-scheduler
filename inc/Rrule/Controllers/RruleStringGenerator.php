<?php

namespace AM\Scheduler\Rrule\Controllers;

use DateTime;
use DateTimeZone;
use AM\Scheduler\Rrule\Enums\RruleParamsEnum;

class RruleStringGenerator
{
    protected ?array $params = null;

    public function __construct(?array $params = null)
    {
        $this->params = $params;
    }

    public function getRruleParamsString(): ?string
    {
        $rrule_string = "";
        $rrule_params_for_string = RruleParamsEnum::array(true);
        $date_fields = [
            RruleParamsEnum::DTSTART->value,
            RruleParamsEnum::UNTIL->value,
        ];
        $required_fields = [RruleParamsEnum::FREQ->value];

        if (!empty($this->params) && !empty($rrule_params_for_string)) {
            foreach ($rrule_params_for_string as $rrule_key => $rrule_value) {
                // date fields
                if (in_array($rrule_value, $date_fields)) {
                    switch ($rrule_value) {
                        case "start_date":
                            $date = $this->createValidDate(
                                $this->params[$rrule_value]
                            );
                            $rrule_string .= "{$rrule_value}={$date};";
                            break;
                        case "until":
                            $date = $this->createValidDate(
                                date: $this->params[$rrule_value]
                            );
                            if ($date === $this->createValidDate()) {
                                $date = $this->createValidDate(
                                    date: $this->params[$rrule_value],
                                    modify: "+ 1 year"
                                );
                            }
                            $rrule_string .= "{$rrule_value}={$date};";
                            break;
                        default:
                            $rrule_string .= "{$rrule_value}={$date};";
                            break;
                    }
                }
                // required fields
                if (in_array($rrule_value, $required_fields)) {
                    switch ($rrule_value) {
                        case "freq":
                            if (empty($this->params[$rrule_value])) {
                                $rrule_string .= "{$rrule_value}=DAILY;";
                            } else {
                                $rrule_string .= "{$rrule_value}={$this->params[$rrule_value]};";
                            }
                            break;
                        default:
                            $rrule_string .= "{$rrule_value}={$date};";
                            break;
                    }
                }
                // all fields excluding date fields and required fields
                if (
                    !empty($this->params[$rrule_value]) &&
                    !in_array($rrule_value, $date_fields) &&
                    !in_array($rrule_value, $required_fields)
                ) {
                    if (is_array($this->params[$rrule_value])) {
                        $val = implode(",", $this->params[$rrule_value]);
                        if (!empty($val)) {
                            $rrule_string .= "{$rrule_value}={$val};";
                        }
                    } else {
                        $rrule_string .= "{$rrule_value}={$this->params[$rrule_value]};";
                    }
                }
            }
        }
        return $rrule_string;
    }

    // default timezone is CET or UTC-6
    public function createValidDate(
        string $date = "",
        string $tz = "America/Guatemala",
        string $modify = "+ 0 days"
    ): ?string {
        if (empty($date)) {
            // ->setTimezone(new DateTimeZone($tz))
            return (new DateTime())->modify($modify)->format("c");
        }
        // ->setTimezone(new DateTimeZone($tz))
        return (new DateTime($date))->modify($modify)->format("c");
    }

    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
