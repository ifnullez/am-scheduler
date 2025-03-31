<?php

namespace AM\Scheduler\Base\Entities;

use AM\Scheduler\Base\Entities\AbstractEntity;
use AM\Scheduler\Base\Entities\EntityFactoryInterface;
use AM\Scheduler\Base\QueryBuilder\MysqlQueryBuilder;

abstract class AbstractEntityFactory implements EntityFactoryInterface
{
    protected $wpdb;
    protected $queryBuilder;
    protected $tableName;

    public function __construct(string $tableName)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->queryBuilder = new MysqlQueryBuilder();
        $this->tableName = $tableName;
    }

    public function create($id): ?AbstractEntity
    {
        try {
            $sql = $this->queryBuilder
                ->select($this->tableName, ["*"])
                ->where("id", (string) $id)
                ->getSQL();

            $data = $this->wpdb->get_row($sql, ARRAY_A);
            return $data ? $this->createEntity($data) : null;
        } catch (\RuntimeException $e) {
            error_log(
                "Entity Factory Error in {$this->tableName}: " .
                    $e->getMessage()
            );
            return null;
        }
    }

    public function createMultiple(
        array $conditions = [],
        ?int $limit = null,
        int $start = 0
    ): array {
        try {
            $builder = $this->queryBuilder->select($this->tableName, ["*"]);

            foreach ($conditions as $field => $value) {
                $builder->where($field, (string) $value);
            }

            if ($limit !== null) {
                $builder->limit($start, $limit);
            }

            $sql = $builder->getSQL();
            $results = $this->wpdb->get_results($sql, ARRAY_A) ?? [];

            return array_map([$this, "createEntity"], $results);
        } catch (\RuntimeException $e) {
            error_log(
                "Entity Factory Error in {$this->tableName}: " .
                    $e->getMessage()
            );
            return [];
        }
    }

    public function createNew(array $data): AbstractEntity
    {
        try {
            $sql = $this->queryBuilder
                ->insert($this->tableName, $data)
                ->getSQL();

            $this->wpdb->query($sql);
            $id = $this->wpdb->insert_id;

            $entity = $this->create($id);
            if (!$entity) {
                throw new \RuntimeException(
                    "Failed to retrieve newly created entity in {$this->tableName}"
                );
            }
            return $entity;
        } catch (\RuntimeException $e) {
            error_log(
                "Entity Factory Error in {$this->tableName}: " .
                    $e->getMessage()
            );
            throw $e;
        }
    }
    /**
     * @param array<int,mixed> $data
     */
    abstract protected function createEntity(array $data): AbstractEntity;
}
