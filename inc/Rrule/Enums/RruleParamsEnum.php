<?php
namespace AM\Scheduler\Rrule\Enums;

use AM\Scheduler\Base\Traits\EnumToArray;

enum RruleParamsEnum: string
{
    use EnumToArray;

    case DTSTART = "start_date";
    case UNTIL = "until";
    case FREQ = "freq";
    case COUNT = "count";
    case INTERVAL = "interval";
    case ADJUSTMONTHEND = "adjust_month_end";
    case BYDAY = "by_day";
    case BYMONTHDAY = "by_month_day";
    case BYYEARDAY = "by_year_day";
    case BYWEEKNO = "by_week_no";
    case BYMONTH = "by_month";
    case BYSETPOS = "by_set_pos";
    case BYHOUR = "by_hour";
    case BYMINUTE = "by_minute";
    case BYSECOND = "by_second";
}
