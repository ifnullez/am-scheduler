<?php

namespace AM\Scheduler\Base\Helpers;

use InvalidArgumentException;

class ObjectHelper
{
    private mixed $data;
    private object $classInstance;
    private array $jsonFields;

    /**
     * @param mixed $data Data to populate into the object
     * @param object $classInstance Target object to populate
     * @param string[] $jsonFields Property names that should be JSON-decoded
     */
    public function __construct(
        mixed $data,
        object $classInstance,
        array $jsonFields = []
    ) {
        $this->data = $data;
        $this->classInstance = $classInstance;
        $this->jsonFields = $jsonFields;
    }

    /**
     * Populates the class instance with the provided data
     *
     * @return object The populated object instance
     * @throws InvalidArgumentException If the class instance is invalid
     */
    public function populateToSelf(): object
    {
        if (!$this->isValidInstance()) {
            throw new InvalidArgumentException(
                "Invalid object instance provided"
            );
        }

        $dataArray = $this->normalizeData();

        if (empty($dataArray)) {
            return $this->classInstance;
        }

        foreach ($dataArray as $property => $value) {
            if (property_exists($this->classInstance, $property)) {
                $this->classInstance->$property = $this->processValue(
                    $property,
                    $value
                );
            }
        }

        return $this->classInstance;
    }

    /**
     * Checks if the class instance is a valid object
     *
     * @return bool
     */
    private function isValidInstance(): bool
    {
        return is_object($this->classInstance);
    }

    /**
     * Normalizes input data to an array
     *
     * @return array
     */
    private function normalizeData(): array
    {
        if (empty($this->data)) {
            return [];
        }

        return is_object($this->data)
            ? get_object_vars($this->data)
            : (array) $this->data;
    }

    /**
     * Processes a value based on whether it should be JSON-decoded
     *
     * @param string $property Property name
     * @param mixed $value Value to process
     * @return mixed Processed value
     */
    private function processValue(string $property, mixed $value): mixed
    {
        if (!in_array($property, $this->jsonFields)) {
            return $value;
        }

        return !empty($value) ? json_decode($value) : null;
    }
}
