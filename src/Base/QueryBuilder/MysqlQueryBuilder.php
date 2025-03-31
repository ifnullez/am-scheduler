<?php

namespace AM\Scheduler\Base\QueryBuilder;

use AM\Scheduler\Base\Helpers\ArraysHelper;
use AM\Scheduler\Base\Helpers\StrHelper;
use AM\Scheduler\Base\QueryBuilder\Interfaces\SQLQueryBuilder;
use RuntimeException;

class MysqlQueryBuilder implements SQLQueryBuilder
{
    private string $base = "";
    private string $type = "";
    private array $where = [];
    private array $parameters = [];
    private ?string $limit = null;

    /**
     * Resets the query builder to its initial state
     */
    private function reset(): void
    {
        $this->base = "";
        $this->type = "";
        $this->where = [];
        $this->parameters = [];
        $this->limit = null;
    }

    /**
     * Builds a SELECT query
     *
     * @param string $table Table name
     * @param string[] $fields Fields to select
     * @return SQLQueryBuilder
     */
    public function select(string $table, array $fields): SQLQueryBuilder
    {
        $this->reset();
        $this->type = "select";
        $this->base =
            "SELECT " .
            implode(", ", $fields) .
            " FROM " .
            $this->prefixTable($table);
        return $this;
    }

    /**
     * Builds an INSERT query
     *
     * @param string $table Table name
     * @param array $data Data to insert
     * @return SQLQueryBuilder
     */
    public function insert(string $table, array $data): SQLQueryBuilder
    {
        $this->reset();
        $this->type = "insert";

        $fields = (new StrHelper(
            (new ArraysHelper($data))->keys()
        ))->toString();
        $placeholders = implode(", ", array_fill(0, count($data), "%s"));
        $this->parameters = array_values($data);

        $this->base = "INSERT INTO {$this->prefixTable(
            $table
        )} ({$fields}) VALUES ({$placeholders})";
        return $this;
    }

    /**
     * Adds a WHERE clause to the query
     *
     * @param string $field Field name
     * @param string $value Value to compare
     * @param string $operator Comparison operator
     * @return SQLQueryBuilder
     * @throws RuntimeException If WHERE is not applicable
     */
    public function where(
        string $field,
        string $value,
        string $operator = "="
    ): SQLQueryBuilder {
        $allowedTypes = ["select", "update", "delete"];
        if (!in_array($this->type, $allowedTypes)) {
            throw new RuntimeException(
                "WHERE clause can only be added to SELECT, UPDATE, or DELETE queries"
            );
        }

        $this->where[] = "{$field} {$operator} %s";
        $this->parameters[] = $value;
        return $this;
    }

    /**
     * Builds an UPDATE query
     *
     * @param string $table Table name
     * @param array $data Data to update
     * @return SQLQueryBuilder
     */
    public function update(string $table, array $data): SQLQueryBuilder
    {
        $this->reset();
        $this->type = "update";

        $setClause = implode(
            ", ",
            array_map(fn($key) => "$key = %s", array_keys($data))
        );
        $this->parameters = array_values($data);

        $this->base = "UPDATE {$this->prefixTable($table)} SET {$setClause}";
        return $this;
    }

    /**
     * Adds a LIMIT clause to the query
     *
     * @param int $start Starting position
     * @param int $offset Number of rows
     * @return SQLQueryBuilder
     * @throws RuntimeException If LIMIT is not applicable
     */
    public function limit(int $start, int $offset): SQLQueryBuilder
    {
        if ($this->type !== "select") {
            throw new RuntimeException(
                "LIMIT clause can only be added to SELECT queries"
            );
        }

        $this->limit = " LIMIT %d, %d";
        $this->parameters[] = $start;
        $this->parameters[] = $offset;
        return $this;
    }

    /**
     * Generates the final SQL query string
     *
     * @return string Complete SQL query
     */
    public function getSQL(): string
    {
        global $wpdb;

        $sql = $this->base;

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        if ($this->limit !== null) {
            $sql .= $this->limit;
        }

        $sql .= ";";

        return empty($this->parameters)
            ? $sql
            : $wpdb->prepare($sql, $this->parameters);
    }

    /**
     * Adds WordPress table prefix to table name
     *
     * @param string $table Table name
     * @return string Prefixed table name
     */
    private function prefixTable(string $table): string
    {
        global $wpdb;
        return $wpdb->prefix . $table;
    }

    /**
     * Builds a DELETE query
     *
     * @param string $table Table name
     * @return SQLQueryBuilder
     */
    public function delete(string $table): SQLQueryBuilder
    {
        $this->reset();
        $this->type = "delete";

        $this->base = "DELETE FROM {$this->prefixTable($table)}";
        return $this;
    }
}
