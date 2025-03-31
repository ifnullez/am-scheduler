<?php
namespace AM\Scheduler\Base\Models;

use AM\Scheduler\Base\Entities\AbstractEntity;

interface BaseModelInterface
{
    /**
     * Retrieve all entities.
     *
     * @return array<int, AbstractEntity>|null
     */
    public function getAll(): ?array;

    /**
     * Find a single entity by a key-value pair.
     *
     * @param string $key The column name to search
     * @param string $value The value to match
     * @return AbstractEntity|null
     */
    public function findBy(string $key, string $value): ?AbstractEntity;

    /**
     * Find entities where any of the specified fields match the search term.
     *
     * @param array<string> $fields Columns to search in
     * @param string $search Value to search for
     * @return array<int, AbstractEntity>|null
     */
    public function findWhere(array $fields, string $search): ?array;

    /**
     * Convert the model to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * Create a new entity.
     *
     * @param array<string, mixed> $data Data to insert
     * @return AbstractEntity
     */
    public function create(array $data): AbstractEntity;

    /**
     * Update an existing entity.
     *
     * @param int $id The ID of the entity to update
     * @param array<string, mixed> $data Data to update
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete an entity by ID.
     *
     * @param int|null $id The ID of the entity to delete
     * @return bool True on success, false on failure
     */
    public function delete(?int $id): bool;
}
