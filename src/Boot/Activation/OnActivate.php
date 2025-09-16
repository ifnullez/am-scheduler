<?php

namespace AM\Scheduler\Boot\Activation;

use AM\Scheduler\Utils\Traits\Singleton;

final class OnActivate
{
    use Singleton;

    public function onCall(): void
    {
        // flush_rewrite_rules();
    }
}
