<?php

namespace AM\Scheduler\Rrule\Enums;

use AM\Scheduler\Base\Traits\EnumToArray;

enum StartDayEnum: string
{
    use EnumToArray;

    case YEARLY = "Yearly";
    case MONTHLY = "Monthly";
    case WEEKLY = "Weekly";
    case DAILY = "Daily";
    case HOURLY = "Hourly";
    case MINUTELY = "Minutely";
    case SECONDLY = "Secondly";
}
