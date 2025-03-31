<?php

namespace AM\Scheduler\Base\Entities;

use AM\Scheduler\Base\Helpers\ObjectHelper;

trait BaseEntityTrait
{
    /**
     * @param array<int,mixed> $data
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $helper = new ObjectHelper($data, $this);
            $helper->populateToSelf();
        }
    }
}
