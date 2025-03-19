<?php

namespace AM\Scheduler\Base\Helpers;

use Generator;
use AM\Scheduler\Base\Traits\Singleton;
use Traversable;

class StaticHelper
{
    use Singleton;

    /**
     * Creates a SQL-like request string.
     *
     * @param array<int, mixed> $data
     */
    public static function createRequestString(
        string $divider,
        array $data,
        string $compare_as,
        string $end = ""
    ): string {
        return rtrim(
            implode(
                $divider,
                array_map(
                    fn($v, $k) => "`{$k}` {$compare_as} '{$v}' {$end}",
                    $data,
                    array_keys($data)
                )
            ),
            $end
        );
    }

    /**
     * Converts dashes and underscores to spaces and capitalizes the first letter.
     */
    public static function clearString(string $line): string
    {
        return ucfirst(str_replace(["-", "_"], " ", $line));
    }

    /**
     * Creates a SQL-like update request string.
     *
     * @param array<int, mixed> $data
     */
    public static function createRequestStringForUpdate(
        array $data,
        string $divider = ", ",
        string $wrapper = "'"
    ): string {
        return rtrim(
            implode(
                $divider,
                array_map(fn($v) => "{$wrapper}{$v}{$wrapper}", $data)
            ),
            $divider
        );
    }

    /**
     * Converts an associative array into a string of HTML attributes.
     *
     * @param array<string, mixed> $attributes
     */
    public static function attributeFromArray(array $attributes = []): string
    {
        return implode(
            " ",
            array_map(
                fn($v, $k) => "{$k}=\"{$v}\"",
                $attributes,
                array_keys($attributes)
            )
        );
    }

    /**
     * Populates an array of keys with a given value in SQL format.
     *
     * @param array<int, string> $keys
     */
    public static function arrayToStringPopulateValue(
        array $keys,
        string $value,
        string $middle = "=",
        string $divider = " ",
        string $end = ""
    ): string {
        $additional_value_wrapper =
            $middle === "LIKE" || $middle === "like" ? "%" : "";

        return rtrim(
            implode(
                $divider,
                array_map(
                    fn(
                        $k
                    ) => "`{$k}` {$middle} '{$additional_value_wrapper}{$value}{$additional_value_wrapper}' {$end}",
                    $keys
                )
            ),
            $end
        );
    }

    /**
     * Recursively resolves nested Traversable structures into a flat key-value iterable.
     *
     * @param Traversable|array<int|string, mixed> $array_of_requests
     * @return Generator<int|string, mixed>
     */
    public static function resolveEntitiesSchemas(
        Traversable|array $array_of_requests
    ): Generator {
        foreach ($array_of_requests as $key => $value) {
            if ($value instanceof Traversable || is_array($value)) {
                yield from self::resolveEntitiesSchemas($value);
            } else {
                yield $key => $value;
            }
        }
    }

    /**
     * Checks if a value exists in an array or matches a given scalar.
     */
    public static function checkSelectedValue(
        string $needle,
        mixed $haystack
    ): bool {
        return is_array($haystack)
            ? in_array($needle, $haystack, false)
            : $needle === $haystack;
    }

    /**
     * Extracts values from an array of associative arrays based on a field.
     *
     * @param array<int, array<string, mixed>> $data
     */
    public static function getFromArray(
        array $data,
        string $field,
        bool $useIdAsKey = true
    ): array {
        $new_data = [];
        foreach ($data as $value) {
            if ($useIdAsKey && isset($value["id"])) {
                $new_data[$value["id"]] = $value[$field] ?? null;
            } else {
                $new_data[] = $value[$field] ?? null;
            }
        }
        return array_filter($new_data, fn($v) => $v !== null);
    }

    /**
     * Filters an array by key-value pairs and returns matching IDs.
     *
     * @param array<int, array<string, mixed>> $data
     */
    public static function getArrayByKeyValue(
        array $data,
        string $key,
        mixed $value
    ): array {
        return array_map(
            fn($v) => $v["id"] ?? null,
            array_filter(
                $data,
                fn($v) => isset($v[$key]) && $v[$key] === $value
            )
        );
    }

    /**
     * Returns the ordinal suffix for a given day number.
     */
    public static function dayNumberFormat(int $number): string
    {
        if ($number >= 11 && $number <= 13) {
            return "{$number}th";
        }

        return match ($number % 10) {
            1 => "{$number}st",
            2 => "{$number}nd",
            3 => "{$number}rd",
            default => "{$number}th",
        };
    }
}
