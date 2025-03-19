<?php

namespace AM\Scheduler\Rrule\Enums;

use AM\Scheduler\Base\Traits\EnumToArray;

enum ByDayEnum
{
    use EnumToArray;

    case MO;
    case TU;
    case WE;
    case TH;
    case FR;
    case SA;
    case SU;

    public static function params(): array
    {
        return [
            ByDayEnum::MO->name => [
                "title" => "Monday",
                "short_title" => "Mon",
            ],
            ByDayEnum::TU->name => [
                "title" => "Tuesday",
                "short_title" => "Tues",
            ],
            ByDayEnum::WE->name => [
                "title" => "Wednesday",
                "short_title" => "Wed",
            ],
            ByDayEnum::TH->name => [
                "title" => "Thursday",
                "short_title" => "Thur",
            ],
            ByDayEnum::FR->name => [
                "title" => "Friday",
                "short_title" => "Fri",
            ],
            ByDayEnum::SA->name => [
                "title" => "Saturday",
                "short_title" => "Sat",
            ],
            ByDayEnum::SU->name => [
                "title" => "Sunday",
                "short_title" => "Sun",
            ],
        ];
    }
}
