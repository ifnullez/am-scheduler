<?php

namespace MHS\Events\Fields;

use MHS\Base\Traits\Singleton;
use MHS\Rrule\Enums\{ByDayEnum, StartDayEnum};

class SidebarFields
{
    use Singleton;

    private static array $args = [];

    public function __construct(array $args = [])
    {
        self::$args = $args;
    }

    public static function getFields(): ?array
    {
        return [
            "start_date" => [
                "type" => "date",
                "label" => "Start Date",
                "value" => date("d-m-Y"),
            ],
            "by_day[]" => [
                "type" => "select",
                "label" => "By day",
                "options" => ByDayEnum::arrayFlattenValue("title"),
                "attributes" => [
                    "multiple" => "true",
                    "size" => 8,
                ],
            ],
            "by_month_day[]" => [
                "type" => "select",
                "label" => "By Month Day",
                "options" => [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6 => 6,
                    7 => 7,
                    8 => 8,
                    9 => 9,
                    10 => 10,
                    11 => 11,
                    12 => 12,
                    13 => 13,
                    14 => 14,
                    15 => 15,
                    16 => 16,
                    17 => 17,
                    18 => 18,
                    19 => 19,
                    20 => 20,
                    21 => 21,
                    22 => 22,
                    23 => 23,
                    24 => 24,
                    25 => 25,
                    26 => 26,
                    27 => 27,
                    28 => 28,
                    29 => 29,
                    30 => 30,
                    31 => 31,
                ],
                "attributes" => [
                    "multiple" => "true",
                    "size" => 8,
                ],
            ],
            "freq" => [
                "type" => "select",
                "label" => "Frequency",
                "options" => StartDayEnum::array(),
                "attributes" => [
                    "size" => 8,
                ],
            ],
            "interval" => [
                "type" => "number",
                "label" => "Interval",
                "value" => 1,
                "attributes" => [
                    "min" => 1,
                ],
            ],
            "count" => [
                "type" => "number",
                "label" => "How many Series willbe generated",
            ],
            "until" => [
                "type" => "date",
                "label" => "Until Date",
            ],
        ];
    }
}
