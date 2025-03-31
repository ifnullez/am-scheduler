<?php
namespace AM\Scheduler\Base\Models;

use AM\Scheduler\Base\Entities\AbstractEntity;
use AM\Scheduler\Base\QueryBuilder\MysqlQueryBuilder;

abstract class AbstractBaseModel implements BaseModelInterface
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
    /**
     * @param array<int,mixed> $data
     */
    abstract protected function createEntity(array $data): AbstractEntity;

    public function getAll(): ?array
    {
        $sql = $this->queryBuilder->select($this->tableName, ["*"])->getSQL();
        $results = $this->wpdb->get_results($sql, ARRAY_A);
        return $results === null || empty($results)
            ? null
            : array_map([$this, "createEntity"], $results);
    }

    public function findBy(string $key, mixed $value): ?AbstractEntity
    {
        $sql = $this->queryBuilder
            ->select($this->tableName, ["*"])
            ->where($key, $value)
            ->limit(0, 1)
            ->getSQL();

        $data = $this->wpdb->get_row($sql, ARRAY_A);
        return $data ? $this->createEntity($data) : null;
    }

    public function findWhere(array $fields, string $search): ?array
    {
        $builder = $this->queryBuilder->select($this->tableName, ["*"]);
        foreach ($fields as $field) {
            $builder->where($field, $search, "LIKE");
        }

        $sql = $builder->getSQL();
        $results = $this->wpdb->get_results($sql, ARRAY_A);
        return $results === null || empty($results)
            ? null
            : array_map([$this, "createEntity"], $results);
    }

    public function toArray(): array
    {
        return [];
    }

    public function delete(?int $id): bool
    {
        if ($id === null) {
            return false;
        }

        $sql = $this->queryBuilder
            ->delete($this->tableName)
            ->where("id", (string) $id)
            ->getSQL();

        return $this->wpdb->query($sql) !== false;
    }

    public function create(array $data): AbstractEntity
    {
        $sql = $this->queryBuilder->insert($this->tableName, $data)->getSQL();

        $this->wpdb->query($sql);
        $id = $this->wpdb->insert_id;

        $entity = $this->findBy("id", (string) $id);
        if (!$entity) {
            throw new \RuntimeException(
                "Failed to retrieve newly created entity in {$this->tableName}"
            );
        }
        return $entity;
    }

    public function update(int $id, array $data): bool
    {
        $sql = $this->queryBuilder
            ->update($this->tableName, $data)
            ->where("id", (string) $id)
            ->getSQL();

        return $this->wpdb->query($sql) !== false;
    }
}
