<?php

namespace AM\Scheduler\Runner;

class SchedulerRunnerReccurences
{
    public static array $intervals = [];

    public function __construct()
    {
        // filling the intervals goes in constructor because sometimes we need the calculations and additional functions calling | make attention - this is wordpress schedulers not woocommerce
        self::$intervals = [
            "weekly" => [
                "interval" => WEEK_IN_SECONDS,
                "display" => "Once Weekly",
            ],
            "each_month" => [
                "interval" => MONTH_IN_SECONDS,
                "display" => "First day of each month",
            ],
            "five_min" => [
                "interval" => MINUTE_IN_SECONDS * 5,
                "display" => "Every five minutes",
            ],
            "daily" => [
                "interval" => DAY_IN_SECONDS,
                "display" => "Daily",
            ],
            "hourly" => [
                "interval" => HOUR_IN_SECONDS,
                "display" => "Hourly",
            ],
            "each_twelve_hours" => [
                "interval" => HOUR_IN_SECONDS * 12,
                "display" => "Each twelve hours",
            ],
            "minutely" => [
                "interval" => MINUTE_IN_SECONDS,
                "display" => "Minutely",
            ],
        ];
        // adding additional reccurences
        add_filter("cron_schedules", [$this, "mh_extra_reccurences"]);
    }

    public function mh_extra_reccurences($schedules)
    {
        return array_merge($schedules, self::$intervals);
    }
}
