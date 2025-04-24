<?php
namespace AM\Scheduler\Base\Contracts\Traits;

use AM\Scheduler\Base\Configs\Config;
use AM\Scheduler\Base\QueryBuilder\MysqlQueryBuilder;
use AM\Scheduler\Base\Traits\GetterTrait;
use AM\Scheduler\Base\Traits\Singleton;
use Exception;
use wpdb;

trait ContractSchemaTrait
{
    use Singleton, GetterTrait;

    protected ?wpdb $wpdb = null;
    private ?MysqlQueryBuilder $queryBuilder;
    private ?string $plugin_slug;

    private function __construct()
    {
        global $wpdb;
        $this->plugin_slug = Config::getInstance()->plugin_slug;
        $this->wpdb = $wpdb;
        $this->queryBuilder = new MysqlQueryBuilder();
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
        } else {
            $sql = $this->queryBuilder->drop("%s%s%s")->getSQL();
            $drop = $this->wpdb->get_results(
                $this->wpdb->prepare(
                    $sql,
                    $this->wpdb->prefix,
                    "{$this->plugin_slug}_",
                    $this->table_name
                )
            );
            if ($drop && !is_wp_error($drop)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array<int,mixed> $indexes
     */
    public function isIndexesExists(array $indexes): ?bool
    {
        if (!empty($indexes)) {
            $sql = $this->queryBuilder
                ->show("INDEXES", `%s%s`)
                ->where("column_name", "%s")
                ->getSQL();
            /** TODO: refactoring needed */
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
            $sql = $this->queryBuilder
                ->select("INFORMATION_SCHEMA.TABLE_CONSTRAINTS", [
                    "1 AS constraint_exists",
                ])
                ->where("TABLE_NAME", "%s%s")
                ->andWhere("CONSTRAINT_NAME", "%s")
                ->limit(1)
                ->getSQL();

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
