<?php

namespace AM\Scheduler\Base\Abstractions;

use AM\Scheduler\Base\Helpers\StaticHelper;
use AM\Scheduler\Base\Interfaces\BaseModelInterface;
use wpdb;

abstract class BaseModel implements BaseModelInterface
{
    protected string $table_name;
    protected wpdb $wpdb;

    public function getAll(): ?array
    {
        $result = $this->wpdb->get_results(
            "SELECT * FROM {$this->wpdb->prefix}{$this->table_name}",
            ARRAY_A
        );
        return $result;
    }

    public function findBy(
        string $key,
        string $value,
        string $operator = "="
    ): ?array {
        $sql = $this->wpdb->get_results(
            "SELECT * FROM {$this->wpdb->prefix}{$this->table_name} WHERE `{$key}` {$operator} '{$value}'",
            ARRAY_A
        );
        return $sql;
    }
    /**
     * @param array<int,mixed> $values
     */
    public function batchFind(string $key, array $values): ?array
    {
        $string_of_values = implode(",", $values);
        if (!empty($string_of_values)) {
            return $this->wpdb->get_results(
                "SELECT * FROM {$this->wpdb->prefix}{$this->table_name} WHERE `{$key}` IN ({$string_of_values}) ORDER BY `created_at` DESC",
                ARRAY_A
            );
        }
        return null;
    }

    public function findOne(string $field, string $value): ?array
    {
        $result = $this->wpdb->get_results(
            "SELECT * FROM {$this->wpdb->prefix}{$this->table_name} WHERE {$field} = {$value} LIMIT 0, 1",
            ARRAY_A
        );
        return !empty($result[0]) ? $result[0] : [];
    }
    /**
     * @param array<int,mixed> $fields_and_values
     * @param mixed $compare_as
     */
    public function multiParamsFind(
        array $fields_and_values,
        $compare_as = "<="
    ): ?array {
        $request_string = StaticHelper::createRequestString(
            data: $fields_and_values,
            divider: "AND",
            compare_as: $compare_as
        );
        return $this->wpdb->get_results(
            "SELECT * FROM {$this->wpdb->prefix}{$this->table_name} WHERE {$request_string}",
            ARRAY_A
        );
    }

    public function findWhere(array $fields, string $search): ?array
    {
        $search_string = StaticHelper::arrayToStringPopulateValue(
            $fields,
            $search,
            "LIKE",
            " ",
            "OR"
        );

        return $this->wpdb->get_results(
            "SELECT * FROM {$this->wpdb->prefix}{$this->table_name} WHERE {$search_string}",
            ARRAY_A
        );
    }

    public function __toArray(array $array = []): ?array
    {
        return get_object_vars($this);
    }
    /**
     * @param array<int,mixed> $array_to_save
     */
    public function save(array $array_to_save): mixed
    {
        if (!empty($array_to_save)) {
            unset($array_to_save["id"]);

            $fields = StaticHelper::createRequestStringForUpdate(
                array_keys($array_to_save),
                ", ",
                "`"
            );
            $values = StaticHelper::createRequestStringForUpdate(
                array_values($array_to_save)
            );

            $is_saved = $this->wpdb->query(
                "INSERT INTO {$this->wpdb->prefix}{$this->table_name} ({$fields}) VALUES ({$values})"
            );

            if ($is_saved) {
                return $this->wpdb->insert_id;
            }
        }
        return false;
    }
    /**
     * @param array<int,mixed> $data
     * @param array<int,mixed> $where_values
     */
    public function batchUpdate(
        array $data,
        string $where_key,
        array $where_values,
        string $operator = "IN"
    ): mixed {
        $id = !empty($data["id"]) ? $data["id"] : null;

        if (!empty($data["id"])) {
            unset($data["id"]);
        }

        if (!empty($data)) {
            $values_string = implode(",", $where_values);
            $records_string = StaticHelper::createRequestString(
                ", ",
                $data,
                "="
            );
            $is_updated = $this->wpdb->query(
                "UPDATE {$this->wpdb->prefix}{$this->table_name} SET $records_string WHERE `{$where_key}` {$operator} ({$values_string})"
            );
            if ($is_updated !== false) {
                return true;
            }
        }
        return false;
    }
    /**
     * @param array<int,mixed> $array_to_update
     */
    public function update(array $array_to_update): mixed
    {
        $id = !empty($array_to_update["id"]) ? $array_to_update["id"] : null;

        if (!empty($array_to_update["id"])) {
            unset($array_to_update["id"]);
        }

        if (!empty($array_to_update)) {
            $records_string = StaticHelper::createRequestString(
                ", ",
                $array_to_update,
                "="
            );

            $is_updated = $this->wpdb->query(
                "UPDATE {$this->wpdb->prefix}{$this->table_name} SET $records_string WHERE `id` = '{$id}'"
            );
            if ($is_updated !== false) {
                return true;
            }
        }
        return false;
    }
    /**
     * @param array<int,mixed> $keys_and_values
     */
    public function batchInsert(array $keys_and_values): ?bool
    {
        if (!empty($keys_and_values)) {
            return $this->wpdb->query(
                "INSERT INTO {$this->wpdb->prefix}{$this->table_name} {$keys_and_values["keys"]} VALUES {$keys_and_values["values"]}"
            );
        }
    }
    /**
     * @param array<int,mixed> $ids
     */
    public function batchDelete(array $ids): ?bool
    {
        $ids = !empty($ids) ? implode(",", $ids) : null;

        if (!empty($ids)) {
            return $this->wpdb->query(
                "DELETE FROM {$this->wpdb->prefix}{$this->table_name} WHERE id IN ({$ids})"
            );
        }
        return false;
    }

    public function delete(?int $id): ?bool
    {
        if (!empty($id)) {
            return $this->wpdb->query(
                $this->wpdb->prepare(
                    "DELETE FROM {$this->wpdb->prefix}{$this->table_name} WHERE id = %d",
                    $id
                )
            );
        }
        return false;
    }

    public function getEntityFieldValue(
        ?int $id = null,
        ?string $field = null
    ): ?array {
        $options = [];
        if (!empty($id) && !empty($field)) {
            $one = $this->findOne("id", $id);
            if (!empty($one) && !empty($one[$field])) {
                $options[] = explode(",", $one[$field]);
            }
        }
        return $options;
    }

    public function getAllAsOptionsArray(): ?array
    {
        $all = $this->getAll();
        $options = [];
        if (!empty($all)) {
            foreach ($all as $entity) {
                $options[$entity["id"]] = $entity["title"];
            }
        }
        return $options;
    }
}
