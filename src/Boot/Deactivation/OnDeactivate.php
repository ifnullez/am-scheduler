<?php

namespace AM\Scheduler\Boot\Deactivation;

use AM\Scheduler\Utils\Traits\Singleton;

final class OnDeactivate
{
    use Singleton;

    public function onCall(): void
    {
        // flush_rewrite_rules();
    }
}
