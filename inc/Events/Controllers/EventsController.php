<?php

namespace MHS\Events\Controllers;

use MHS\AdminPages\Pages\EventsAdminPage;
use MHS\Base\Abstractions\AbstractItemController;
use MHS\Base\Traits\Singleton;
use MHS\Base\Views\ViewsController;
use MHS\Entities\Entity\EventsEntity;
use MHS\Events\Models\EventsModel;
use MHS\ListTables\EventsListTable;
use MHS\Events\Event;
use MHS\Events\Fields\{EditableFields, SidebarFields};
use MHS\Rrule\Controllers\RruleOccurences;
use MHS\Series\Models\SeriesModel;
use MHS\Tasks\Models\TasksModel;

class EventsController extends AbstractItemController
{
    use Singleton;

    private function __construct()
    {
        $this->slug = EventsAdminPage::getInstance()->slug;
        $this->table_name = EventsEntity::getInstance()->table_name;
        $this->onSave();

        // fill the fields
        $this->editable_fields = EditableFields::getInstance([
            "slug" => $this->slug,
            "table_name" => $this->table_name,
        ])::getFields();
        $this->sidebar_fields = SidebarFields::getInstance([
            "slug" => $this->slug,
            "table_name" => $this->table_name,
        ])::getFields();
    }

    public function list(): void
    {
        // ViewsController::loadTemplate("/template-parts/notice.php", [
        //        "controller" => $this::getInstance(),
        //        "notice" => $this->notice,
        // ]);
        ViewsController::loadTemplate("/template-parts/title-button.php", [
            "controller" => $this::getInstance(),
        ]);
        ViewsController::loadTemplate("/template-parts/forms/view.php", [
            "table" => new EventsListTable(),
            "controller" => $this::getInstance(),
        ]);
    }

    public function delete(int $id): void
    {
        if (EventsModel::getInstance()->delete($id)) {
            exit(wp_redirect($this->rootPageUrl()));
        }
    }

    public function edit(int $id): void
    {
        ViewsController::loadTemplate("/template-parts/title-button.php", [
            "controller" => $this::getInstance(),
            "title" => "Edit Event",
        ]);
        ViewsController::loadTemplate("/admin/events/actions/edit.php", [
            "item" => new Event($id),
            "controller" => $this::getInstance(),
        ]);
    }

    public function new(): void
    {
        ViewsController::loadTemplate("/template-parts/title-button.php", [
            "controller" => $this::getInstance(),
            "title" => "New Event",
        ]);
        ViewsController::loadTemplate("/admin/events/actions/new.php", [
            "controller" => $this::getInstance(),
        ]);
    }

    // TODO: Needed to optimize method in the feature
    public function onSave(array $data = [], $return_item_id = false): ?int
    {
        $data = !empty($data) ? $data : $_POST;
        $new_id = null;
        if (
            !empty($data["slug_name"]) &&
            $data["slug_name"] === "mh_scheduler"
        ) {
            // unset not existing fields
            unset($data["action"]);
            unset($data["slug_name"]);

            $r = new RruleOccurences($data);

            // fields marked as json it is needed to save using special way
            $json_fields = ["members_ids", "meta"];

            // build array for saving data
            $data_for_save_update = $this->toSaveUpdateArray(
                $data,
                $json_fields
            );

            $data_for_save_update["pattern"] = $r->pattern;
            $data_for_save_update["start_date"] = $r->rrule->startDate->format(
                "c"
            );
            $data_for_save_update["until"] = $r->rrule->until->format("c");

            // event save
            if (empty($data["id"])) {
                // save data
                $new_id = EventsModel::getInstance()->save(
                    $data_for_save_update
                );
            } else {
                // update event
                EventsModel::getInstance()->update($data_for_save_update);
            }
            // search related data for event and manipulate them
            $id_for_related_records = !empty($new_id) ? $new_id : $data["id"];
            $task = null;
            if (!empty($data_for_save_update["task_id"])) {
                $task = TasksModel::getInstance()->findOne(
                    "id",
                    $data_for_save_update["task_id"]
                );
            }

            $group_id = !empty($task["group_id"]) ? $task["group_id"] : null;
            $members_ids = !empty($task["members_ids"])
                ? $task["members_ids"]
                : null;

            if (!empty($r->rrule->occurrences) && !empty($task)) {
                $values = [];
                $keys =
                    "(title,event_id,members_ids, group_id, task_id, author_id, starting_at, amount, action, message, reason)";
                foreach ($r->rrule->occurrences as $occurence) {
                    $starting_at = $occurence->format("c");
                    $values[] = "(
                    'Series for: {$data["title"]}',
                    '{$id_for_related_records}',
                    '{$members_ids}',
                    '{$group_id}',
                    '{$task["id"]}',
                    '{$data_for_save_update["author_id"]}',
                    '{$starting_at}',
                    '{$task["amount"]}',
                    '{$task["action"]}',
                    '{$task["message"]}',
                    '{$task["reason"]}'
                    )";
                }

                $toSave = [
                    "keys" => $keys,
                    "values" => implode(", ", $values),
                ];

                if (empty($new_id)) {
                    // edit procedure
                    $findedSeries = SeriesModel::getInstance()->findBy(
                        "event_id",
                        $data["id"]
                    );

                    if (!empty($findedSeries)) {
                        $finded_series_ids = [];

                        foreach ($findedSeries as $one) {
                            $finded_series_ids[] = $one["id"];
                        }

                        if (!empty($finded_series_ids)) {
                            SeriesModel::getInstance()->batchDelete(
                                $finded_series_ids
                            );
                            SeriesModel::getInstance()->batchInsert($toSave);
                        }
                    } else {
                        SeriesModel::getInstance()->batchInsert($toSave);
                    }
                } else {
                    SeriesModel::getInstance()->batchInsert($toSave);
                }
            }
            if (!$return_item_id) {
                return exit(wp_redirect($this->rootPageUrl()));
            }
        }
        return $new_id;
    }
}
