<?php
namespace MHS\Rrule\Enums;

use MHS\Base\Traits\EnumToArray;

enum ActionsEnum: string
{
    use EnumToArray;

    case SEND_POINTS_MEMBER = "Send points to the member";
    case NOTIFY_MEMBER = "Notify member";
    case NONE = "none";
}
