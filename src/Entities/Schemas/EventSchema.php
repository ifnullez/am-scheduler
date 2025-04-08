<?php

namespace AM\Scheduler\Entities\Schemas;

use AM\Scheduler\Base\Contracts\Abstractions\AbstractContract;
use AM\Scheduler\Base\Contracts\Interfaces\ContractInterface;
use AM\Scheduler\Base\Contracts\Traits\ContractSchemaTrait;

class EventSchema extends AbstractContract implements ContractInterface
{
    use ContractSchemaTrait;

    public function up(): ?bool
    {
        return false;
    }
    public function down(): ?bool
    {
        return false;
    }
    public function schema(): ?string
    {
        return null;
    }
    public function update(): ?array
    {
        return null;
    }
}
