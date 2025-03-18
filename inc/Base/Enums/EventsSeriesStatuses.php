<?php

namespace MHS\Base\Enums;

use MHS\Base\Traits\EnumToArray;

enum EventsSeriesStatuses: string
{
    use EnumToArray;

    case NEW = "planned";
    case FAILED = "failed";
    case EXEC = "executing_now";
    case DONE = "done";
}
