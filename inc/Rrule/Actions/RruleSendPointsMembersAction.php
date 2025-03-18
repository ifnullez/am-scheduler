<?php
namespace MHS\Rrule\Actions;

use Members;
use MHS\Base\Enums\EventsSeriesStatuses;
use MHS\Base\Helpers\StaticHelper;
use MHS\Base\Traits\Singleton;
use MHS\Groups\Controllers\GroupsController;
use MHS\Integrations\MakeCom\MakeComCli;
use MHS\Rrule\Abstractions\RruleActionAbstraction;
use MHS\Rrule\Controllers\RruleStringGenerator;
use MHS\Rrule\Enums\ActionsEnum;
use MHS\Rrule\Interfaces\RruleActionInterface;
use MHS\Series\Controllers\SeriesController;
use MHS\Tasks\Task;

class RruleSendPointsMembersAction extends RruleActionAbstraction implements
    RruleActionInterface
{
    use Singleton;

    private function __construct()
    {
        $this->action = ActionsEnum::SEND_POINTS_MEMBER->name;
        $this->onCall();
    }

    public function onCall(array $series = []): void
    {
        // for messages log
        $body_for_log = [];
        // to update series
        $array_for_update = [];

        if (!empty($series)) {
            $for_members = !empty($series["members_ids"])
                ? json_decode($series["members_ids"])
                : null;
            $from_group = !empty($series["group_id"])
                ? GroupsController::getOwner($series["group_id"])
                : null;
            $amount = !empty($series["amount"]) ? $series["amount"] : null;
            $action = !empty($series["action"]) ? $series["action"] : null;

            if(!empty($for_members) && !empty($from_group["ID"]) && !empty($amount) && !empty($action)){
                unset($series["execution_status"]);
                // for series update
                $array_for_update = [
                    "executed_at" => (new RruleStringGenerator())->createValidDate(),
                    "execution_status" => EventsSeriesStatuses::FAILED->value
                ];

                $series_for_log = StaticHelper::createRequestString(", \r", $series, "=>");


                $isEnoughPoints = GroupsController::isEnoughAmountForTransaction(
                    owner_id: $from_group["ID"],
                    amount: $amount,
                    members_count: count($for_members ?? [])
                );

                if($isEnoughPoints){

                    // send points
                    $executed = false;
                    foreach($for_members as $member_id){

                    $group_id = !empty($series["group_id"]) ? $series["group_id"] : 0;
                        $executed = Members::transferPointsFromGroupToMember($member_id, $amount, $group_id, 0, (new Task($series["task_id"]))->description ?? "", "");
                    }
                    if($executed){
                        // for series update
                        $array_for_update = [
                            "executed_at" => (new RruleStringGenerator())->createValidDate(),
                            "execution_status" => EventsSeriesStatuses::DONE->value
                        ];

                        $series_for_log = StaticHelper::createRequestString(", \r", $series, "=>");
                        $body_for_log = [
                            "message" => print_r(
                                "\r Scheduler: \r Series Executed Successfully! \r {$series_for_log} \r",
                                true
                            ),
                        ];
                    }
                } else if(!empty($from_group["ID"])) {
                    // insufficient amount
                    $body_for_log = [
                        "message" => print_r(
                            "\r Scheduler: Group has insufficient amount for transaction \r {$series_for_log} \r",
                            true
                        ),
                    ];
                } else {
                    // another errors
                    $body_for_log = [
                        "message" => print_r(
                            "\r Scheduler: The transaction is not executed, something went wrong! \r Series: \r {$series_for_log} \r",
                            true
                        ),
                    ];
                }
                // update series
                SeriesController::getInstance()->updateData($array_for_update, [$series["id"]], "id");

                if(!empty($body_for_log)){
                    $mk = new MakeComCli("https://hook.us1.make.com/ue8a3zi1zuo280vr461yn2fcj6ocrhe7", $body_for_log);
                    $mk->sendRequest();
                }
            }
        }
    }
}
