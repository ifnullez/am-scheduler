<?php

namespace MHS\Entities;

use MHS\Base\Helpers\StaticHelper;
use MHS\Base\Traits\Singleton;
use MHS\Entities\Entity\{EventsEntity, SeriesEntity, TasksEntity};

class EntitiesLoader
{
    use Singleton;

    private EventsEntity $events;
    private TasksEntity $tasks;
    private SeriesEntity $series;

    private function __construct()
    {
        $this->events = EventsEntity::getInstance();
        $this->tasks = TasksEntity::getInstance();
        $this->series = SeriesEntity::getInstance();

        // up entities tables
        $this->upEntities();
    }

    private function upEntities(): void
    {
        $entities = get_object_vars($this);
        if (!empty($entities)) {
            foreach ($entities as $entity_name => $entity) {
                if (method_exists($entity, "up")) {
                    $entity->up();
                }
            }
            foreach ($entities as $entity_for_alter) {
                $schema = $entity_for_alter->updateSchema();
                if (!empty($schema)) {
                    foreach (StaticHelper::resolveEntitiesSchemas($schema) as $schema_request_key => $schema_request) {
                        // here we checking indexes because indexes don't have named keys, they always will be numeric
                        if (is_int($schema_request_key)) {
                            if (!$entity_for_alter->isIndexesExists($entity_for_alter->indexes)) {
                                $entity_for_alter->wpdb->query($schema_request);
                            }
                        }
                        // here we checking constraints the keys in constraints array should be the same as constraint name
                        // this is needed to execute each constraint separately if it's not exist in database
                        if (is_string($schema_request_key)) {
                            if (!$entity_for_alter->isConstraintExists($schema_request_key)) {
                                $entity_for_alter->wpdb->query($schema_request);
                            }
                        }
                    }
                }
            }
        }
    }
}
