<?php

namespace AM\Scheduler\Rrule\Enums;

use AM\Scheduler\Base\Traits\EnumToArray;

enum RepeatsEveryEnum: string
{
    use EnumToArray;

    case FIRST = "1";
    case SECOND = "2";
    case THIRD = "3";
    case FOURTH = "4";
    case LAST = "-1";
}
