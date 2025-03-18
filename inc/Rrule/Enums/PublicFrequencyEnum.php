<?php

namespace MHS\Rrule\Enums;

use MHS\Base\Traits\EnumToArray;

enum PublicFrequencyEnum: string
{
    use EnumToArray;

    case DO_NOT_REPEAT = "Do not repeat";
    case YEARLY = "repeats yearly";
    case MONTHLY = "repeats monthly";
    case WEEKLY = "repeats weekly";
    case DAILY = "repeats daily";
    // case HOURLY = "Hourly";
    // case MINUTELY = "Minutely";
    // case SECONDLY = "Secondly";
}
