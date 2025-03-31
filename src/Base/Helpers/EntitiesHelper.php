<?php

namespace AM\Scheduler\Base\Helpers;

use Generator;
use Traversable;

class EntitiesHelper
{
    /**
     * Recursively resolves entity schemas from a traversable collection or array.
     *
     * @param Traversable|array $entities The collection of entities to resolve
     * @return Generator<int|string, mixed> Yields key-value pairs from the resolved entities
     */
    public static function resolveEntitiesSchemas(
        Traversable|array $entities
    ): Generator {
        // Early return for empty input
        if (empty($entities)) {
            return;
        }

        foreach ($entities as $key => $value) {
            if (self::isTraversableValue($value)) {
                // Recursively yield from nested traversable structures
                yield from self::resolveEntitiesSchemas($value);
            } else {
                // Yield scalar or non-traversable values
                yield $key => $value;
            }
        }
    }

    /**
     * Checks if a value is traversable or a generator.
     *
     * @param mixed $value The value to check
     * @return bool True if the value is traversable, false otherwise
     */
    private static function isTraversableValue(mixed $value): bool
    {
        return $value instanceof Traversable ||
            $value instanceof Generator ||
            is_array($value);
    }
}
