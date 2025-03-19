<?php

namespace AM\Scheduler\Tasks\Controllers;

use AM\Scheduler\AdminPages\Pages\TasksAdminPage;
use AM\Scheduler\Base\Abstractions\AbstractItemController;
use AM\Scheduler\Base\Helpers\StaticHelper;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Base\Views\ViewsController;
use AM\Scheduler\Entities\Entity\TasksEntity;
use AM\Scheduler\Events\Models\EventsModel;
use AM\Scheduler\Tasks\Models\TasksModel;
use AM\Scheduler\ListTables\TasksListTable;
use AM\Scheduler\Series\Controllers\SeriesController;
use AM\Scheduler\Tasks\Fields\{EditableFields, SidebarFields};
use AM\Scheduler\Tasks\Task;

class TasksController extends AbstractItemController
{
    use Singleton;

    private function __construct()
    {
        $this->slug = TasksAdminPage::getInstance()->slug;
        $this->table_name = TasksEntity::getInstance()->table_name;
        // contentnt area fields
        $this->editable_fields = EditableFields::getInstance([
            "slug" => $this->slug,
            "table_name" => $this->table_name,
        ])::getFields();
        // sidebar
        $this->sidebar_fields = SidebarFields::getInstance([
            "slug" => $this->slug,
            "table_name" => $this->table_name,
        ])::getFields();

        $this->onSave();
    }

    public function list(): void
    {
        // ViewsController::loadTemplate("/template-parts/notice.php", [
        //     "controller" => $this::getInstance(),
        //     "notice" => $this->notice,
        // ]);
        ViewsController::loadTemplate("/template-parts/title-button.php", [
            "controller" => $this::getInstance(),
        ]);
        ViewsController::loadTemplate("/template-parts/forms/view.php", [
            "table" => new TasksListTable(),
            "controller" => $this::getInstance(),
        ]);
    }

    public function delete(int $id): void
    {
        // currently we not maintain actions when we can't delete item
        if (TasksModel::getInstance()->delete($id)) {
            exit(wp_redirect($this->rootPageUrl()));
        }
    }

    public function edit(int $id): void
    {
        ViewsController::loadTemplate("/template-parts/title-button.php", [
            "controller" => $this::getInstance(),
            "title" => "Edit Task",
        ]);
        ViewsController::loadTemplate("/admin/tasks/actions/edit.php", [
            "item" => new Task($id),
            "controller" => $this::getInstance(),
        ]);
    }

    public function new(): void
    {
        ViewsController::loadTemplate("/template-parts/title-button.php", [
            "controller" => $this::getInstance(),
            "title" => "New Task",
        ]);
        ViewsController::loadTemplate("/admin/tasks/actions/new.php", [
            "controller" => $this::getInstance(),
        ]);
    }

    public function onSave(array $data = [], $return_item_id = false): ?int
    {
        $data = !empty($data) ? $data : $_POST;
        $task_id = null;

        if (
            !empty($data["slug_name"]) &&
            $data["slug_name"] === "mh_scheduler_tasks"
        ) {
            // unset not existing table fields
            unset($data["slug_name"]);

            $json_fields = ["members_ids", "meta"];
            $data_for_save_update = $this->toSaveUpdateArray(
                $data,
                $json_fields
            );
            if (empty($data["id"])) {
                // save data
                $task_id = TasksModel::getInstance()->save(
                    $data_for_save_update
                );
            } else {
                TasksModel::getInstance()->update($data_for_save_update);
                $related_event = EventsModel::getInstance()->findBy(
                    "task_id",
                    $data_for_save_update["id"]
                );
                $task_events = StaticHelper::getFromArray($related_event, "id");
                $for_series_update = [];
                $include_fields = [
                    "members_ids",
                    "group_id",
                    "amount",
                    "action",
                    "reason",
                    "message",
                ];
                if (!empty($data_for_save_update)) {
                    foreach (
                        $data_for_save_update
                        as $for_save_key => $for_save_value
                    ) {
                        if (in_array($for_save_key, $include_fields)) {
                            $for_series_update[$for_save_key] = $for_save_value;
                        }
                    }
                    // update depend series
                    SeriesController::getInstance()->updateData(
                        $for_series_update,
                        $task_events
                    );
                }
            }
            if (!$return_item_id) {
                return exit(wp_redirect($this->rootPageUrl()));
            }
        }
        return $task_id;
    }
}
