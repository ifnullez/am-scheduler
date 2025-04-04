<?php

namespace AM\Scheduler\Base\Helpers;

class StrHelper
{
    private mixed $data;

    /**
     * @param mixed $input Initial input (string or ArraysHelper)
     */
    public function __construct(mixed $input)
    {
        $this->data = $this->normalizeInput($input);
    }

    public function stripSpacesAround(): self
    {
        if (is_string($this->data)) {
            $this->data = trim($this->data);
        }
        return $this;
    }

    /**
     * Normalizes whitespace in the string by removing excess spaces
     *
     * @return self
     */
    public function extractWords(): self
    {
        if (is_string($this->data)) {
            $this->data = trim(preg_replace("/\s+/", " ", $this->data));
        }
        return $this;
    }

    public function stripEmojis(?string $text = null): self
    {
        $this->data = str_replace("?", "{%}", $this->data);
        $this->data = mb_convert_encoding($this->data, "ISO-8859-1", "UTF-8");
        $this->data = mb_convert_encoding($this->data, "UTF-8", "ISO-8859-1");
        $this->data = preg_replace("/(\s?\?\s?)/", " ", $this->data);
        $this->data = str_replace("{%}", "?", $this->data);

        return $this;
    }

    /**
     * Parses a query string into an array
     *
     * @return self
     */
    public function fromQueryString(): self
    {
        if (is_string($this->data)) {
            parse_str($this->data, $this->data);
        }
        return $this;
    }

    /**
     * Wraps each element with the specified wrapper
     *
     * @param string $wrapper Character/string to wrap around each element
     * @return self
     */
    public function wrapEach(string $wrapper = "'"): self
    {
        $array = is_array($this->data)
            ? $this->data
            : $this->extractWords()->toArray()->data;

        $this->data = (new ArraysHelper($array))->wrapEach($wrapper);
        return $this;
    }

    /**
     * Splits the string into an array
     *
     * @param string $divider Delimiter to split on
     * @return self
     */
    public function toArray(string $divider = " "): self
    {
        if (is_string($this->data)) {
            $this->data = explode($divider, $this->data);
        }
        return $this;
    }

    /**
     * Joins array elements into a string
     *
     * @param string $divider Separator between elements
     * @return self
     */
    public function toString(string $divider = ", "): self
    {
        if (is_array($this->data)) {
            $this->data = implode($divider, $this->data);
        } elseif ($this->data instanceof ArraysHelper) {
            $this->data = implode($divider, $this->data->get());
        }
        return $this;
    }

    /**
     * Returns the current value as a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->normalizeOutput();
    }

    /**
     * Gets the current value
     *
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->normalizeOutput();
    }

    public function dropWords(?string $words = null): self
    {
        if (is_string($this->data)) {
            $this->data = str_replace([$words], [], $this->data);
        }
        return $this;
    }

    public function toLower(): self
    {
        if (is_string($this->data)) {
            $this->data = strtolower($this->data);
        }
        return $this;
    }

    public function toUpper(): self
    {
        if (is_string($this->data)) {
            $this->data = strtoupper($this->data);
        }
        return $this;
    }

    public function capitalize(): self
    {
        if (is_string($this->data)) {
            $this->data = ucfirst($this->data);
        }
        return $this;
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

    /**
     * Normalizes input data
     *
     * @param mixed $input
     * @return mixed
     */
    private function normalizeInput(mixed $input): mixed
    {
        return $input instanceof ArraysHelper ? $input->get() : $input;
    }

    /**
     * Normalizes output data
     *
     * @return mixed
     */
    private function normalizeOutput(): mixed
    {
        return $this->data instanceof ArraysHelper
            ? $this->data->get()
            : $this->data;
    }
}
