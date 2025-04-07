<?php
namespace AM\Scheduler\Admin\Abstractions;

abstract class AbstractAdminPage
{
    protected ?string $slug;
    protected ?string $menu_slug;
    protected ?float $menu_position = 21.8312365;

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
