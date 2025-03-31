<?php

namespace AM\Scheduler\Base\Helpers;

class NumbersHelper
{
    private mixed $number;

    /**
     * @param int $number Initial number to work with
     */
    public function __construct(int $number)
    {
        $this->number = $number;
    }

    /**
     * Converts the number to its ordinal form (e.g., 1st, 2nd, 3rd, 4th)
     *
     * @param int|null $number Optional number to convert; uses stored number if null
     * @return self Returns instance for method chaining
     */
    public function toOrdinal(?int $number = null): self
    {
        $value = $number ?? $this->number;

        // Handle special case for numbers 11-13
        if ($value >= 11 && $value <= 13) {
            $this->number = "{$value}th";
            return $this;
        }

        $this->number = match ($value % 10) {
            1 => "{$value}st",
            2 => "{$value}nd",
            3 => "{$value}rd",
            default => "{$value}th",
        };

        return $this;
    }

    /**
     * Gets the current number value
     *
     * @return mixed Current number (int or string after ordinal conversion)
     */
    public function get(): mixed
    {
        return $this->number;
    }

    /**
     * Magic getter for properties
     *
     * @param string $property Property name
     * @return mixed Property value or null if not exists
     */
    public function __get(string $property): mixed
    {
        return $this->$property ?? null;
    }
}
