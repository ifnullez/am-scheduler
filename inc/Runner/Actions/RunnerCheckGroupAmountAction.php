<?php

namespace MHS\Runner\Actions;

use MHS\Base\Traits\Singleton;
use MHS\Groups\Controllers\GroupsController;
use MHS\Integrations\MakeCom\MakeComCli;
use MHS\Runner\Abstractions\RunnerActionAbstraction;
use MHS\Runner\Interfaces\RunnerActionInterface;
use WC_Points_Rewards_Manager;

class RunnerCheckGroupAmountAction extends RunnerActionAbstraction implements RunnerActionInterface
{
    use Singleton;
    // TODO: Send email to the group owner
    public function onCall(array $series = []): void
    {
        $collected = [];
        if(!empty($series)){
            foreach($series as $series_item){
                $members = !empty($series_item["members_ids"]) ? json_decode($series_item["members_ids"]) : [];
                $group_id = !empty($series_item["group_id"]) ? $series_item["group_id"] : null;
                $group_owner = !empty($group_id) ? GroupsController::getOwner($group_id) : null;
                $amount_for_each_member = !empty($series_item["amount"]) ? $series_item["amount"] : 0;


                $collected_members = !empty($collected[$group_owner["ID"]]["members"]) ? $collected[$group_owner["ID"]]["members"] : [];
                $collected_amount = !empty($collected[$group_owner["ID"]]["amount_for_each_member"]) ? $collected[$group_owner["ID"]]["amount_for_each_member"] : 0;

                $collected[$group_owner["ID"]] = [
                    "members" => array_unique(array_merge($collected_members, $members)),
                    "amount_for_each_member" => $amount_for_each_member + $collected_amount,
                    "group_id" => $group_id
                ];
            }
        }
        if(!empty($collected)){
            foreach($collected as $group_owner_id => $data){
                $is_group_have_enough_points = GroupsController::isEnoughAmountForTransaction(
                    owner_id: $group_owner_id,
                    amount: $data["amount_for_each_member"],
                    members_count: count($data["members"])
                );
                $group_points = WC_Points_Rewards_Manager::get_users_points($group_owner_id);
                $needed_amount = $data["amount_for_each_member"] * count($data["members"]);
                $group_info = get_post($data["group_id"]);

                if(!$is_group_have_enough_points){
                    $mk = new MakeComCli("https://hook.us1.make.com/ue8a3zi1zuo280vr461yn2fcj6ocrhe7", [
                        "message" => "\r The group: \r Name: {$group_info->post_title} \r ID: {$data["group_id"]} \r  Owner: {$group_owner_id} \r has insufficient amount for transactions to the next 3 days! \r Needed amount of points {$needed_amount} \r the group has {$group_points} points. \r"
                    ]);
                    $mk->sendRequest();
                }
            }
        }
    }
}
