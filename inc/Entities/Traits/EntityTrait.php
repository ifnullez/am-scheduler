<?php

namespace AM\Scheduler\Entities\Traits;

use Exception;
use AM\Scheduler\Base\Traits\Singleton;

trait EntityTrait
{
    use Singleton;

    private function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function up(): ?bool
    {
        if (empty($this->table_name)) {
            throw new Exception(
                "Please fill the table_name parameter in class $this::class"
            );
        }
        // exec table schema
        if (!function_exists("dbDelta")) {
            require_once ABSPATH . "wp-admin/includes/upgrade.php";
        }
        // create table
        dbDelta($this->schema(), true);

        return true;
    }

    public function down(): ?bool
    {
        if (empty($this->table_name)) {
            throw new Exception(
                "Please fill the table_name parameter in class $this::class"
            );
        }
        return true;
    }

    public function isIndexesExists(array $indexes): ?bool
    {
        if (!empty($indexes)) {
            foreach ($indexes as $index_name) {
                $funded_indexes = $this->wpdb->query(
                    "SHOW INDEXES FROM `{$this->wpdb->prefix}{$this->table_name}` WHERE Column_name = '{$index_name}';"
                );
                if (!empty($funded_indexes)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public function isConstraintExists(string $constraint_name): ?bool
    {
        if (!empty($constraint_name)) {
            $constraint = $this->wpdb->get_results(
                "SELECT COUNT(*) AS constraint_exists
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                WHERE TABLE_NAME = '{$this->wpdb->prefix}{$this->table_name}'
                AND CONSTRAINT_NAME = '{$constraint_name}' LIMIT 1;"
            );

            $finded_count = array_shift($constraint)->constraint_exists;

            if (intval($finded_count) > 0) {
                return true;
            }
            return false;
        }
    }
}
