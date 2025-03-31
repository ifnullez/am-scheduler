<?php

namespace AM\Scheduler\Base\Entities;

use AM\Scheduler\Base\Entities\AbstractEntity;

interface EntityFactoryInterface
{
    /**
     * Create a single entity by ID.
     *
     * @param mixed $id The entity ID
     * @return AbstractEntity|null
     */
    public function create($id): ?AbstractEntity;

    /**
     * Create multiple entities based on conditions.
     *
     * @param array<string, string> $conditions Key-value pairs for WHERE clauses
     * @param int|null $limit Number of entities to return
     * @param int $start Starting offset
     * @return array<int, AbstractEntity>
     */
    public function createMultiple(
        array $conditions = [],
        ?int $limit = null,
        int $start = 0
    ): array;

    /**
     * Create a new entity and persist it to the database.
     *
     * @param array<string, mixed> $data Data to insert
     * @return AbstractEntity
     */
    public function createNew(array $data): AbstractEntity;
}
