<?php

namespace AM\Scheduler\Base\QueryBuilder\Interfaces;

interface SQLQueryBuilder
{
    public function reset(): SQLQueryBuilder;
    public function select(string $table, array $fields): SQLQueryBuilder;
    public function from(string $table): SQLQueryBuilder;
    public function insert(string $table, array $data): SQLQueryBuilder;
    public function update(string $table, array $data): SQLQueryBuilder;
    public function delete(string $table): SQLQueryBuilder;
    public function show(
        string $statement,
        ?string $target = null
    ): SQLQueryBuilder;
    public function create(
        string $table,
        array $columns,
        bool $ifNotExists = false
    ): SQLQueryBuilder;
    public function alter(string $table): SQLQueryBuilder;
    public function addColumn(
        string $name,
        string $type,
        array $options = []
    ): SQLQueryBuilder;
    public function addIndex(string $name, array $columns): SQLQueryBuilder;
    public function drop(string $table): SQLQueryBuilder;

    public function where(
        string $field,
        mixed $value,
        string $operator = "="
    ): SQLQueryBuilder;
    public function andWhere(
        string $field,
        mixed $value,
        string $operator = "="
    ): SQLQueryBuilder;
    public function orWhere(
        string $field,
        mixed $value,
        string $operator = "="
    ): SQLQueryBuilder;

    /**
     * Adds an AND condition to the WHERE clause
     * @param string $field Field name
     * @param mixed $value Value to compare
     * @param string $operator Comparison operator
     * @return SQLQueryBuilder
     */
    public function and(
        string $field,
        mixed $value,
        string $operator = "="
    ): SQLQueryBuilder;

    /**
     * Adds an OR condition to the WHERE clause
     * @param string $field Field name
     * @param mixed $value Value to compare
     * @param string $operator Comparison operator
     * @return SQLQueryBuilder
     */
    public function or(
        string $field,
        mixed $value,
        string $operator = "="
    ): SQLQueryBuilder;

    public function having(
        string $field,
        mixed $value,
        string $operator = "="
    ): SQLQueryBuilder;
    public function andHaving(
        string $field,
        mixed $value,
        string $operator = "="
    ): SQLQueryBuilder;
    public function orHaving(
        string $field,
        mixed $value,
        string $operator = "="
    ): SQLQueryBuilder;
    public function groupBy(string|array $fields): SQLQueryBuilder;
    public function limit(int $count, ?int $offset = null): SQLQueryBuilder;
    public function orderBy(
        string $field,
        string $direction = "ASC"
    ): SQLQueryBuilder;
    public function join(
        string $table,
        string $condition,
        string $type = "INNER"
    ): SQLQueryBuilder;
    public function getSQL(): string;
}
