<?php

namespace AM\Scheduler\Utils\Helpers;

class StrHelper
{
    private mixed $data;

    public function __construct(string|ArraysHelper $input)
    {
        $this->data = $input instanceof ArraysHelper ? $input->get() : $input;
    }

    public function stripSpacesAround(): self
    {
        if (is_string($this->data)) {
            $this->data = trim($this->data);
        }
        return $this;
    }

    public function extractWords(): self
    {
        if (is_string($this->data)) {
            $this->data = trim(preg_replace("/\s+/", " ", $this->data));
        }
        return $this;
    }

    public function stripEmojis(): self
    {
        if (!is_string($this->data)) {
            return $this;
        }

        // Remove most common emojis (doesn't cover all)
        $this->data = preg_replace("/[\x{1F600}-\x{1F6FF}]/u", "", $this->data);
        $this->data = preg_replace("/[\x{1F300}-\x{1F5FF}]/u", "", $this->data);
        $this->data = preg_replace("/[\x{1F900}-\x{1F9FF}]/u", "", $this->data);
        $this->data = preg_replace("/[\x{2600}-\x{26FF}]/u", "", $this->data);

        return $this;
    }

    public function fromQueryString(): self
    {
        if (is_string($this->data)) {
            parse_str($this->data, $this->data);
        }
        return $this;
    }

    public function wrapEach(string $wrapper = "'"): self
    {
        $array = is_array($this->data)
            ? $this->data
            : $this->extractWords()->toArray()->data;

        $this->data = (new ArraysHelper($array))->wrapEach($wrapper);
        return $this;
    }

    public function toArray(string $divider = " "): self
    {
        if (is_string($this->data)) {
            $this->data = explode($divider, $this->data);
        }
        return $this;
    }

    public function toString(string $divider = ", "): self
    {
        if (is_array($this->data)) {
            $this->data = implode($divider, $this->data);
        } elseif ($this->data instanceof ArraysHelper) {
            $this->data = implode($divider, $this->data->get());
        }
        return $this;
    }

    public function dropWords(array $words = []): self
    {
        if (is_string($this->data)) {
            $this->data = str_replace($words, "", $this->data);
        }
        return $this;
    }

    public function prepend(string $word): self
    {
        if (is_string($this->data)) {
            $this->data = $word . $this->data;
        }
        return $this;
    }

    public function append(string $word): self
    {
        if (is_string($this->data)) {
            $this->data .= $word;
        }
        return $this;
    }

    public function toCamelCase(): self
    {
        if (is_string($this->data)) {
            $this->data = lcfirst(
                str_replace(
                    " ",
                    "",
                    ucwords(strtr($this->data, ["_" => " ", "-" => " "]))
                )
            );
        }
        return $this;
    }

    public function replaceWords(
        array $wordsFrom = [],
        array $wordsTo = []
    ): self {
        if (is_string($this->data)) {
            $this->data = str_replace($wordsFrom, $wordsTo, $this->data);
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

    public function stripLeft(string $char): self
    {
        if (is_string($this->data)) {
            $this->data = ltrim($this->data, $char);
        }
        return $this;
    }

    public function stripRight(string $char): self
    {
        if (is_string($this->data)) {
            $this->data = rtrim($this->data, $char);
        }
        return $this;
    }

    public function stripBoth(string $char): self
    {
        if (is_string($this->data)) {
            $this->data = trim($this->data, $char);
        }
        return $this;
    }

    public function get(): mixed
    {
        return $this->normalizeOutput();
    }

    public function __toString(): string
    {
        return (string) $this->normalizeOutput();
    }

    private function normalizeOutput(): mixed
    {
        return $this->data instanceof ArraysHelper
            ? $this->data->get()
            : $this->data;
    }
}
