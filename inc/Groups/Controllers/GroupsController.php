<?php

namespace MHS\Groups\Controllers;

use MHS\Base\Traits\Singleton;
use MHS\Groups\Models\GroupsModel;
use MHS\Members\Models\MembersModel;
use MHS\Series\Models\SeriesModel;
use WC_Points_Rewards_Manager;
use WP_Query;

class GroupsController
{
    use Singleton;

    public static function getAll(): ?array
    {
        $args = new WP_Query([
            "post_status" => "publish",
            "post_type" => "groups",
            "posts_per_page" => "-1"
        ]);
        $groups_array = [];
        if ($args->have_posts()) {
            while ($args->have_posts()) {
                $args->the_post();
                $groups_array[get_the_ID()] = get_the_title();
            }
            wp_reset_query();
            wp_reset_postdata();
        }
        return $groups_array;
    }

    public static function getOwner(?int $group_id): ?array
    {
        if (!empty($group_id)) {
            $group_email = get_field('group_account_email', $group_id);
            $group_nicename = substr($group_email, 0, strpos($group_email, "@"));
            $members = MembersModel::getInstance()->findBy(key: "user_nicename", value: $group_nicename, operator: "LIKE");
            $point_owner_user = array_shift($members);
            return $point_owner_user;
        }
        return null;
    }

    public static function isEnoughAmountForTransaction(?int $owner_id, ?int $amount, ?int $members_count = 1): ?bool
    {
        if (!empty($owner_id) && !empty($amount)) {
            $group_points = WC_Points_Rewards_Manager::get_users_points($owner_id);
            $needed_points_count = $amount * $members_count;

            if ($group_points >= $needed_points_count) {
                return true;
            }
        }
        return false;
    }

    public static function getDataForDatePeriod(int $group_id, string $start_date, string $end_date): ?array
    {
        $group_series_for_period = GroupsModel::getInstance()->getSeriesBetweenDates($group_id, $start_date, $end_date);

        $result = [];

        if (!empty($group_series_for_period)) {
            foreach ($group_series_for_period as $series_item) {
                $members_array = json_decode($series_item["members_ids"]);
                $needed_amount = $series_item["amount"] * count($members_array ?? []);
                $points_amount = !empty($result["points_amount_to_send"]) ? $result["points_amount_to_send"] + $needed_amount : $needed_amount;
                $already_added_members = !empty($result["members"]) ? array_unique($result["members"]) : [];

                $result = [
                    "points_amount_to_send" => $points_amount,
                    "members" => [
                        ...$already_added_members,
                        ...$members_array
                    ]
                ];
            }
            $result["series"] = $group_series_for_period;
            $result["avarage_amount"] = !empty($result["points_amount_to_send"]) ? $result["points_amount_to_send"] / count($result["series"] ?? [1]) : 0;
        }
        return $result;
    }
}
