<?php

namespace AM\Scheduler\Base\QueryBuilder\Interfaces;

interface SQLQueryBuilder
{
    /**
     *   @param $table string
     *   @param $fields array<int|string, mixed>
     *   @return SQLQueryBuilder
     */
    public function select(string $table, array $fields): SQLQueryBuilder;

    /**
     *   @param $field string
     *   @param $value string
     *   @param $operator string
     *   @return SQLQueryBuilder
     */
    public function where(
        string $field,
        string $value,
        string $operator = "="
    ): SQLQueryBuilder;
    /**
     *   @param $table string
     *   @param $data array<int|string, mixed>
     *   @return SQLQueryBuilder
     */
    public function insert(string $table, array $data): SQLQueryBuilder;
    /**
     *   @param $start int
     *   @param $offset int
     *   @return SQLQueryBuilder
     */
    public function limit(int $start, int $offset): SQLQueryBuilder;
    /**
     *   @return string
     */
    public function getSQL(): string;
}
