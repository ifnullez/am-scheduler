<?php
namespace AM\Scheduler\Utils\Traits;

trait SetterTrait
{
    public function __set(mixed $name, mixed $value): void
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }
}
