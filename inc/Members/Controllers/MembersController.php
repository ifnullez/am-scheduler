<?php
namespace AM\Scheduler\Members\Controllers;

class MembersController
{
    public function getAll(): ?array
    {
        $users = get_users();
        $filtered_users = [];

        if ($users) {
            foreach ($users as $user) {
                if (!preg_match("/\bgroup\b/", $user->user_nicename)) {
                    $filtered_users[$user->ID] = $user->display_name;
                }
            }
        }

        return $filtered_users;
    }
}
