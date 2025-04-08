<?php
namespace AM\Scheduler\Base\Contracts\Traits;

use AM\Scheduler\Base\Traits\Singleton;
use Exception;
use wpdb;

trait ContractSchemaTrait
{
    use Singleton;

    protected ?wpdb $wpdb = null;

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

    /**
     * @param array<int,mixed> $indexes
     */
    public function isIndexesExists(array $indexes): ?bool
    {
        if (!empty($indexes)) {
            $sql = "SHOW INDEXES FROM `%s%s`
            WHERE column_name = '%s';";
            foreach ($indexes as $index_name) {
                $funded_indexes = $this->wpdb->query(
                    $this->wpdb->prepare(
                        $sql,
                        $this->wpdb->prefix,
                        $this->table_name,
                        $index_name
                    )
                );
                if (!empty($funded_indexes)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isConstraintExists(string $constraint_name): ?bool
    {
        if (!empty($constraint_name)) {
            $sql = "SELECT 1 AS constraint_exists
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                WHERE TABLE_NAME = '%s%s'
                AND CONSTRAINT_NAME = '%s'
                LIMIT 1;";
            $constraint = $this->wpdb->get_results(
                $this->wpdb->prepare(
                    $sql,
                    $this->wpdb->prefix,
                    $this->table_name,
                    $constraint_name
                )
            );

            $finded_count = array_shift($constraint)->constraint_exists;

            if (intval($finded_count) > 0) {
                return true;
            }
        }
        return false;
    }
}
