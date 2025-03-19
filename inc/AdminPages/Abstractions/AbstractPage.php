<?php
namespace AM\Scheduler\AdminPages\Abstractions;

class AbstractPage
{
    protected string $slug;
    protected string $parent_slug;

    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public function __set(string $property, mixed $value): void
    {
        $this->$property = $value;
    }
}
