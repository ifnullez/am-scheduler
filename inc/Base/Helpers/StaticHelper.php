<?php

namespace AM\Scheduler\Base\Helpers;

use Generator;
use AM\Scheduler\Base\Traits\Singleton;
use Traversable;

class StaticHelper
{
    use Singleton;

    public static function createRequestString(
        string $divider,
        array $data,
        string $compare_as,
        string $end = ""
    ): ?string {
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

    public static function clearString(string $line): ?string
    {
        return str_replace(["-", "_"], [" ", " "], ucfirst($line));
    }

    public static function createRequestStringForUpdate(
        array $data,
        string $divider = ", ",
        string $wrapper = "'"
    ): ?string {
        return rtrim(
            implode(
                $divider,
                array_map(
                    fn($v) => "{$wrapper}{$v}{$wrapper}",
                    $data,
                    array_keys($data)
                )
            ),
            $divider
        );
    }

    public static function attributeFromArray(
        array $attributes_array = []
    ): ?string {
        return rtrim(
            implode(
                " ",
                array_map(
                    fn($v, $k) => "{$k}=\"{$v}\"",
                    $attributes_array,
                    array_keys($attributes_array)
                )
            )
        );
    }

    public static function arrayToStringPopulateValue(
        array $keys,
        string $value,
        string $middle = "=",
        string $divider = " ",
        string $end = ""
    ): ?string {
        return rtrim(
            implode(
                $divider,
                array_map(function ($k) use ($middle, $value, $end) {
                    $additional_value_wrapper = "";
                    if ($middle == "LIKE" || $middle == "like") {
                        $additional_value_wrapper = "%";
                    }
                    return "`{$k}` {$middle} '{$additional_value_wrapper}{$value}{$additional_value_wrapper}' {$end}";
                }, $keys)
            ),
            $end
        );
    }

    public static function resolveEntitiesSchemas(
        Traversable|array $array_of_requests
    ): Traversable {
        if (!empty($array_of_requests)) {
            foreach ($array_of_requests as $request_key => $request_value) {
                if (
                    $request_value instanceof Traversable ||
                    $request_value instanceof Generator ||
                    is_array($request_value)
                ) {
                    yield from self::resolveEntitiesSchemas($request_value);
                } else {
                    yield $request_key => $request_value;
                }
            }
        }
    }

    public static function checkSelectedValue(
        string $needle,
        mixed $heystack
    ): ?bool {
        if (is_array($heystack) && in_array($needle, $heystack, false)) {
            return true;
        } elseif (!is_array($heystack) && $needle == $heystack) {
            return true;
        }
        return false;
    }

    public static function getFromArray(
        array $data,
        string $field,
        $use_id_as_key = true
    ): ?array {
        $new_data = [];
        if (!empty($data) && !empty($field)) {
            foreach ($data as $value) {
                if ($use_id_as_key) {
                    $new_data[$value["id"]] = $value[$field];
                } else {
                    $new_data[] = $value[$field];
                }
            }
        }
        return $new_data;
    }

    public static function getArrayByKeyValue(
        array $data,
        string $key,
        mixed $value
    ): ?array {
        $new_data = [];
        if (!empty($data) && !empty($key)) {
            foreach ($data as $v) {
                if ($v[$key] == $value) {
                    $new_data[] = $v["id"];
                }
            }
        }
        return $new_data;
    }

    public static function dayNumberFormat(int $number): ?string
    {
        if ($number > 3 && $number < 21) {
            return "{$number}th";
        }

        switch ($number % 10) {
            case 1:
                return "{$number}st";
            case 2:
                return "{$number}nd";
            case 3:
                return "{$number}rd";
            default:
                return "{$number}th";
        }
    }
}
