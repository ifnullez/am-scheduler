<?php

namespace AM\Scheduler\Base\Enums;

use AM\Scheduler\Base\Traits\EnumToArray;

enum EventsSeriesStatuses: string
{
    use EnumToArray;

    case NEW = "planned";
    case FAILED = "failed";
    case EXEC = "executing_now";
    case DONE = "done";
}
