<?php

namespace MHS\Rrule\Enums;

use MHS\Base\Traits\EnumToArray;

enum RulesEnum: string
{
    use EnumToArray;

    case FREQ = "FREQ";
    case BYDAY = "BYDAY";
    case BYMONTHDAY = "BYMONTHDAY";
    case COUNT = "COUNT";
    case UNTIL = "UNTIL";
    case INTERVAL = "INTERVAL";
}
