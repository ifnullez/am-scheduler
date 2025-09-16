<?php

namespace AM\Scheduler\Utils\Helpers;

class ArraysHelper
{
    private ?array $data;

    /**
     * @param array|null $input Initial array to work with
     */
    public function __construct(?array $input = null)
    {
        $this->data = $input;
    }

    /**
     * Wraps each array value with the specified wrapper string
     *
     * @param string $wrapper Character/string to wrap around each value
     * @return self Returns instance for method chaining
     */
    public function wrapEach(string $wrapper = "'"): self
    {
        if ($this->data === null) {
            return $this;
        }

        $this->data = array_map(
            static fn($value) => "{$wrapper}{$value}{$wrapper}",
            $this->data
        );

        return $this;
    }

    /**
     * Replaces current array with its keys
     *
     * @return self Returns instance for method chaining
     */
    public function keys(): self
    {
        $this->data = $this->data === null ? [] : array_keys($this->data);
        return $this;
    }

    /**
     * Replaces current array with its values
     *
     * @return self Returns instance for method chaining
     */
    public function values(): self
    {
        $this->data = $this->data === null ? [] : array_values($this->data);
        return $this;
    }

    /**
     * Gets the current array state
     *
     * @return array|null Current array data
     */
    public function get(): ?array
    {
        return $this->data;
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
