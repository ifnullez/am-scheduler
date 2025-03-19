<?php
namespace AM\Scheduler\Rrule\Enums;

use AM\Scheduler\Base\Traits\EnumToArray;

enum ActionsEnum: string
{
    use EnumToArray;

    case SEND_POINTS_MEMBER = "Send points to the member";
    case NOTIFY_MEMBER = "Notify member";
    case NONE = "none";
}
