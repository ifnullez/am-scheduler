<?php

namespace AM\Scheduler\Utils\Traits;

trait EnumToArray
{
    /**
     * Retrieves enum case names as an array
     *
     * @return string[]
     */
    public static function names(): array
    {
        return array_column(self::cases(), "name");
    }

    /**
     * Retrieves enum case values as an array
     *
     * @return array
     */
    public static function values(): array
    {
        $values = array_column(self::cases(), "value");

        return $values !== []
            ? $values
            : (method_exists(self::class, "params")
                ? array_values(self::params())
                : []);
    }

    /**
     * Creates an associative array of names and values
     *
     * @param bool $reverse Whether to swap keys and values
     * @return array|null Returns null if arrays can't be combined
     */
    public static function array(bool $reverse = false): ?array
    {
        $names = self::names();
        $values = self::values();

        return $reverse
            ? self::combineArrays($values, $names)
            : self::combineArrays($names, $values);
    }

    /**
     * Extracts specific values from nested enum arrays
     *
     * @param string|int $valueKey Key to extract
     * @return array|null Returns null if extraction fails
     */
    public static function arrayFlattenValue(string|int $valueKey): ?array
    {
        if (empty($valueKey)) {
            return null;
        }

        $enumArray = self::array();
        if (empty($enumArray)) {
            return null;
        }

        $result = [];
        foreach ($enumArray as $key => $value) {
            if (!is_array($value) || !isset($value[$valueKey])) {
                return null;
            }
            $result[$key] = $value[$valueKey];
        }

        return $result;
    }

    /**
     * Combines two arrays if possible
     *
     * @param array $keys
     * @param array $values
     * @return array|null
     */
    private static function combineArrays(array $keys, array $values): ?array
    {
        return count($keys) === count($values) && !empty($keys)
            ? array_combine($keys, $values)
            : null;
    }
}
