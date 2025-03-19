<?php
namespace AM\Scheduler\Base\Abstractions;

abstract class BaseObject
{
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

    public function populateProperties(array $data = []): void
    {
        if (!empty($data)) {
            foreach ($data as $property => $value) {
                if (property_exists($this, $property) && !empty($value)) {
                    if ($property == "meta") {
                        $this->$property = json_decode($value);
                    } else {
                        $this->$property = $value;
                    }
                }
            }
        }
    }

    public static function getParams(): ?array
    {
        return array_keys(get_class_vars(static::class) ?? []);
    }
}
