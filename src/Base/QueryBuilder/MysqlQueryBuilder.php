<?php

namespace AM\Scheduler\Base\QueryBuilder;

use AM\Scheduler\Base\QueryBuilder\Interfaces\SQLQueryBuilder;
use RuntimeException;

class MysqlQueryBuilder implements SQLQueryBuilder
{
    private string $type = "";
    private string $baseQuery = "";
    private array $whereConditions = [];
    private array $havingConditions = [];
    private array $parameters = [];
    private ?string $limitClause = null;
    private ?string $orderByClause = null;
    private ?string $groupByClause = null;
    private array $joins = [];
    private array $columns = [];
    private array $alterActions = [];

    public function reset(): self
    {
        $this->type = "";
        $this->baseQuery = "";
        $this->whereConditions = [];
        $this->havingConditions = [];
        $this->parameters = [];
        $this->limitClause = null;
        $this->orderByClause = null;
        $this->groupByClause = null;
        $this->joins = [];
        $this->columns = [];
        $this->alterActions = [];
        return $this;
    }

    public function select(string $table, array $fields): self
    {
        $this->reset();
        $this->type = "select";
        $this->baseQuery = sprintf(
            "SELECT %s",
            implode(", ", array_map([$this, "escapeIdentifier"], $fields))
        );
        $this->from($table);
        return $this;
    }

    public function from(string $table): self
    {
        $this->baseQuery .= sprintf(" FROM %s", $this->prefixTable($table));
        return $this;
    }

    public function insert(string $table, array $data): self
    {
        $this->reset();
        $this->type = "insert";

        $fields = array_keys($data);
        $placeholders = array_fill(0, count($data), "%s");
        $this->parameters = array_values($data);

        $this->baseQuery = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->prefixTable($table),
            implode(", ", $fields),
            implode(", ", $placeholders)
        );
        return $this;
    }

    public function update(string $table, array $data): self
    {
        $this->reset();
        $this->type = "update";

        $setClause = array_map(
            fn($key) => sprintf("%s = %%s", $key),
            array_keys($data)
        );
        $this->parameters = array_values($data);

        $this->baseQuery = sprintf(
            "UPDATE %s SET %s",
            $this->prefixTable($table),
            implode(", ", $setClause)
        );
        return $this;
    }

    public function delete(string $table): self
    {
        $this->reset();
        $this->type = "delete";
        $this->baseQuery = sprintf(
            "DELETE FROM %s",
            $this->prefixTable($table)
        );
        return $this;
    }

    public function show(string $statement, ?string $target = null): self
    {
        $this->reset();
        $this->type = "show";
        $this->baseQuery = "SHOW " . strtoupper($statement);
        if ($target !== null) {
            $this->baseQuery .= " FROM " . $this->prefixTable($target);
        }
        return $this;
    }

    public function create(
        string $table,
        array $columns,
        bool $ifNotExists = false
    ): self {
        $this->reset();
        $this->type = "create";
        $this->baseQuery = sprintf(
            "CREATE TABLE %s %s",
            $ifNotExists ? "IF NOT EXISTS" : "",
            $this->prefixTable($table)
        );
        $this->columns = $columns;
        return $this;
    }

    public function alter(string $table): self
    {
        $this->reset();
        $this->type = "alter";
        $this->baseQuery = sprintf(
            "ALTER TABLE %s",
            $this->prefixTable($table)
        );
        return $this;
    }

    public function addColumn(
        string $name,
        string $type,
        array $options = []
    ): self {
        if ($this->type !== "alter" && $this->type !== "create") {
            throw new RuntimeException(
                "addColumn can only be used with CREATE or ALTER queries"
            );
        }

        $columnDef = sprintf(
            "%s %s",
            $this->escapeIdentifier($name),
            strtoupper($type)
        );
        if (!empty($options)) {
            if (isset($options["nullable"]) && !$options["nullable"]) {
                $columnDef .= " NOT NULL";
            }
            if (isset($options["default"])) {
                $columnDef .= sprintf(
                    " DEFAULT %s",
                    $this->quoteValue($options["default"])
                );
            }
            if (
                isset($options["auto_increment"]) &&
                $options["auto_increment"]
            ) {
                $columnDef .= " AUTO_INCREMENT";
            }
        }

        if ($this->type === "create") {
            $this->columns[] = $columnDef;
        } else {
            $this->alterActions[] = sprintf("ADD COLUMN %s", $columnDef);
        }
        return $this;
    }

    public function addIndex(string $name, array $columns): self
    {
        if ($this->type !== "alter" && $this->type !== "create") {
            throw new RuntimeException(
                "addIndex can only be used with CREATE or ALTER queries"
            );
        }

        $indexDef = sprintf(
            "INDEX %s (%s)",
            $this->escapeIdentifier($name),
            implode(", ", array_map([$this, "escapeIdentifier"], $columns))
        );

        if ($this->type === "create") {
            $this->columns[] = $indexDef;
        } else {
            $this->alterActions[] = sprintf("ADD %s", $indexDef);
        }
        return $this;
    }

    public function drop(string $table): self
    {
        $this->reset();
        $this->type = "drop";
        $this->baseQuery = sprintf(
            "DROP TABLE IF EXISTS %s",
            $this->prefixTable($table)
        );
        return $this;
    }

    public function where(
        string $field,
        mixed $value,
        string $operator = "="
    ): self {
        $this->validateWhereApplicable();
        $this->whereConditions[] = ["AND", "$field $operator %s"];
        $this->parameters[] = $value;
        return $this;
    }

    public function andWhere(
        string $field,
        mixed $value,
        string $operator = "="
    ): self {
        return $this->where($field, $value, $operator);
    }

    public function orWhere(
        string $field,
        mixed $value,
        string $operator = "="
    ): self {
        $this->validateWhereApplicable();
        $this->whereConditions[] = ["OR", "$field $operator %s"];
        $this->parameters[] = $value;
        return $this;
    }

    public function and(
        string $field,
        mixed $value,
        string $operator = "="
    ): self {
        $this->validateWhereApplicable();
        $this->whereConditions[] = ["AND", "$field $operator %s"];
        $this->parameters[] = $value;
        return $this;
    }

    public function or(
        string $field,
        mixed $value,
        string $operator = "="
    ): self {
        $this->validateWhereApplicable();
        $this->whereConditions[] = ["OR", "$field $operator %s"];
        $this->parameters[] = $value;
        return $this;
    }

    public function having(
        string $field,
        mixed $value,
        string $operator = "="
    ): self {
        if ($this->type !== "select") {
            throw new RuntimeException(
                "HAVING clause can only be used with SELECT queries"
            );
        }
        $this->havingConditions[] = ["AND", "$field $operator %s"];
        $this->parameters[] = $value;
        return $this;
    }

    public function andHaving(
        string $field,
        mixed $value,
        string $operator = "="
    ): self {
        return $this->having($field, $value, $operator);
    }

    public function orHaving(
        string $field,
        mixed $value,
        string $operator = "="
    ): self {
        if ($this->type !== "select") {
            throw new RuntimeException(
                "HAVING clause can only be used with SELECT queries"
            );
        }
        $this->havingConditions[] = ["OR", "$field $operator %s"];
        $this->parameters[] = $value;
        return $this;
    }

    public function groupBy(string|array $fields): self
    {
        if ($this->type !== "select") {
            throw new RuntimeException(
                "GROUP BY clause can only be used with SELECT queries"
            );
        }

        $fields = is_array($fields) ? $fields : [$fields];
        $this->groupByClause = sprintf(
            " GROUP BY %s",
            implode(", ", array_map([$this, "escapeIdentifier"], $fields))
        );
        return $this;
    }

    public function limit(int $count, ?int $offset = null): self
    {
        if ($this->type !== "select") {
            throw new RuntimeException(
                "LIMIT clause can only be used with SELECT queries"
            );
        }

        if ($offset === null) {
            $this->limitClause = " LIMIT %d";
            $this->parameters[] = $count;
        } else {
            $this->limitClause = " LIMIT %d, %d";
            $this->parameters[] = $offset;
            $this->parameters[] = $count;
        }
        return $this;
    }

    public function orderBy(string $field, string $direction = "ASC"): self
    {
        if ($this->type !== "select") {
            throw new RuntimeException(
                "ORDER BY clause can only be used with SELECT queries"
            );
        }
        $direction = strtoupper($direction) === "DESC" ? "DESC" : "ASC";
        $this->orderByClause = sprintf(
            " ORDER BY %s %s",
            $this->escapeIdentifier($field),
            $direction
        );
        return $this;
    }

    public function join(
        string $table,
        string $condition,
        string $type = "INNER"
    ): self {
        $this->joins[] = sprintf(
            " %s JOIN %s ON %s",
            strtoupper($type),
            $this->prefixTable($table),
            $condition
        );
        return $this;
    }

    public function getSQL(): string
    {
        global $wpdb;

        $sql = $this->baseQuery;

        if ($this->type === "create" && !empty($this->columns)) {
            $sql .= " (" . implode(", ", $this->columns) . ")";
            $sql .= " " . $wpdb->get_charset_collate();
        } elseif ($this->type === "alter" && !empty($this->alterActions)) {
            $sql .= " " . implode(", ", $this->alterActions);
        } elseif (
            $this->type !== "show" &&
            $this->type !== "drop" &&
            $this->type !== "create" &&
            $this->type !== "alter"
        ) {
            if (!empty($this->joins)) {
                $sql .= implode("", $this->joins);
            }
        }

        if (!empty($this->whereConditions)) {
            $whereParts = [];
            foreach ($this->whereConditions as $index => [$logic, $condition]) {
                $whereParts[] = ($index === 0 ? "" : " $logic ") . $condition;
            }
            $sql .= " WHERE " . implode("", $whereParts);
        }

        if ($this->type === "select") {
            if ($this->groupByClause !== null) {
                $sql .= $this->groupByClause;
            }
            if (!empty($this->havingConditions)) {
                $havingParts = [];
                foreach (
                    $this->havingConditions
                    as $index => [$logic, $condition]
                ) {
                    $havingParts[] =
                        ($index === 0 ? "" : " $logic ") . $condition;
                }
                $sql .= " HAVING " . implode("", $havingParts);
            }
            if ($this->orderByClause !== null) {
                $sql .= $this->orderByClause;
            }
            if ($this->limitClause !== null) {
                $sql .= $this->limitClause;
            }
        }

        $sql .= ";";

        return empty($this->parameters)
            ? $sql
            : $wpdb->prepare($sql, $this->parameters);
    }

    private function prefixTable(string $table): string
    {
        global $wpdb;
        return $wpdb->prefix . $table;
    }

    private function validateWhereApplicable(): void
    {
        $allowedTypes = ["select", "update", "delete", "show"];
        if (!in_array($this->type, $allowedTypes)) {
            throw new RuntimeException(
                "WHERE clause can only be used with SELECT, UPDATE, DELETE, or SHOW queries"
            );
        }
    }

    private function escapeIdentifier(string $identifier): string
    {
        return "`" . str_replace("`", "``", $identifier) . "`";
    }

    private function quoteValue(mixed $value): string
    {
        if (is_string($value)) {
            return "'$value'";
        } elseif (is_null($value)) {
            return "NULL";
        }
        return (string) $value;
    }
}
