<?php

namespace MHS\Base\Abstractions;

use DateTime;
use MHS\Base\Helpers\StaticHelper;
use MHS\Base\Interfaces\ItemControllerInterface;
use MHS\Base\Views\ViewsController;

abstract class AbstractItemController implements ItemControllerInterface
{
    protected string $slug;
    protected string $table_name;
    protected array $editable_fields = [];
    protected array $sidebar_fields = [];
    protected array $notice = [];

    public array $input_types = [
        "hidden",
        "date",
        "text",
        "checkbox",
        "radio",
        "number",
    ];

    public array $select_types = ["select"];
    public array $textarea = ["textarea"];
    public array $json_fields = ["meta", "members_ids"];


    public function conditionalDisplay(array $request = []): void
    {
        $request = !empty($request) ? $request : $_GET;

        switch (@$request["action"]) {
            case "edit":
                if (empty($request["element"])) {
                    echo "Please provide element ID";
                } else {
                    $this->edit($request["element"]);
                }
                break;
            case "delete":
                if (empty($request["element"])) {
                    echo "Please provide element ID";
                } else {
                    $this->delete($request["element"]);
                }
                break;
            case "new":
                $this->new();
                break;
            default:
                $this->list();
                break;
        }
    }

    public function rootPageUrl(): string
    {
        return admin_url("/admin.php?page={$this->slug}");
    }

    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public function getSidebar($item = null): void
    {
        $this->getFields($item, $this->sidebar_fields);
    }

    public function getContent($item = null): void
    {
        $this->getFields($item, $this->editable_fields);
    }

    private function getFields($item = null, array $fields = []): void
    {
        if (!empty($fields)) {
            foreach ($fields as $field_slug => $field_value) {
                // check and get the values
                $field_slug = str_replace("[]", "", $field_slug);
                $item_value = @$item->$field_slug;

                $value_on_create = !empty($field_value["value"])
                    ? $field_value["value"]
                    : "";

                if (!empty($_GET["action"]) && $_GET["action"] === "edit") {
                    if (!empty($item_value)) {
                        $value = $item->$field_slug;
                    } else {
                        $value = $value_on_create;
                    }
                } else {
                    $value = $value_on_create;
                }
                // "until"
                $dates_fields = ["start_date"];
                if (in_array($field_slug, $dates_fields)) {
                    $value = (new DateTime($value))->format("Y-m-d");
                }
                // inputs
                if (in_array($field_value["type"], $this->input_types)) {
                    ViewsController::loadTemplate(
                        "/template-parts/fields/input.php",
                        [
                            "name" => $field_slug,
                            "id" =>
                            $field_value["id"] ??
                                $field_slug . "_" . uniqid(),
                            "type" => $field_value["type"] ?? "text",
                            "label" => $field_value["label"] ?? null,
                            "value" => $value,
                            "attributes" => StaticHelper::attributeFromArray(
                                $field_value["attributes"] ?? []
                            ),
                            "info" => $field_value["info"] ?? null,
                        ]
                    );
                }
                // dropdowns
                if (in_array($field_value["type"], $this->select_types)) {
                    if (isset($field_value["attributes"]["multiple"])) {
                        if (in_array($field_slug, $this->json_fields)) {
                            $value = json_decode($value);
                        } else {
                            $value = explode(",", $value);
                        }
                        $field_slug = "{$field_slug}[]";
                    }
                    ViewsController::loadTemplate(
                        "/template-parts/fields/select.php",
                        [
                            "name" => $field_slug,
                            "id" =>
                            $field_value["id"] ??
                                $field_slug . "_" . uniqid(),
                            "label" => $field_value["label"] ?? null,
                            "options" => $field_value["options"] ?? [],
                            "value" => $value,
                            "attributes" => StaticHelper::attributeFromArray(
                                $field_value["attributes"] ?? []
                            ),
                            "info" => $field_value["info"] ?? null,
                        ]
                    );
                }
                // textareas
                if (in_array($field_value["type"], $this->textarea)) {
                    ViewsController::loadTemplate(
                        "/template-parts/fields/textarea.php",
                        [
                            "name" => $field_slug,
                            "id" =>
                            $field_value["id"] ??
                                $field_slug . "_" . uniqid(),
                            "label" => $field_value["label"] ?? null,
                            "value" => $value,
                            "attributes" => StaticHelper::attributeFromArray(
                                $field_value["attributes"] ?? []
                            ),
                            "info" => $field_value["info"] ?? null,
                        ]
                    );
                }
            }
        }
    }

    public function toSaveUpdateArray(
        array $data,
        array $json_fields = []
    ): ?array {
        $data_for_save_update = [];

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) && !in_array($key, $json_fields)) {
                    $data_for_save_update[$key] = implode(",", $value);
                } elseif (is_array($value) && in_array($key, $json_fields)) {
                    $data_for_save_update[$key] = json_encode($value);
                } else {
                    $data_for_save_update[$key] = $value;
                }
            }
        }
        return $data_for_save_update;
    }
}
