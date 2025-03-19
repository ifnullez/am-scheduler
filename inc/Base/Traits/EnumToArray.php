<?php

namespace AM\Scheduler\Base\Traits;

trait EnumToArray
{
    public static function names(): array
    {
        return array_column(self::cases(), "name");
    }

    public static function values(): array
    {
        $backed_enum_values = array_column(self::cases(), "value");
        if (
            empty($backed_enum_values) &&
            method_exists(self::class, "params")
        ) {
            return array_values(self::params());
        }
        return $backed_enum_values;
    }

    public static function array(bool $reverse = false): ?array
    {
        // TODO: the array can't be a key
        if ($reverse && !is_array(self::values())) {
            return array_combine(self::values(), self::names());
        }
        return array_combine(self::names(), self::values());
    }

    public static function arrayFlattenValue(string|int $value_key): ?array
    {
        $full_array = [];
        if (!empty($value_key) && !empty(self::array())) {
            foreach (self::array() as $parent_key => $value) {
                if (is_array($value)) {
                    $full_array[$parent_key] = $value[$value_key];
                } else {
                    return null;
                }
            }
        }
        return $full_array;
    }
}
