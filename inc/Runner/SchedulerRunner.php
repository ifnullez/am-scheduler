<?php

namespace AM\Scheduler\Runner;

use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Integrations\MakeCom\MakeComCli;
use AM\Scheduler\Rrule\Enums\ActionsEnum;
use AM\Scheduler\Rrule\RruleActionsLoader;
use AM\Scheduler\Runner\Controllers\RunnerController;
use AM\Scheduler\Runner\Traits\SchedulerRunnerTrait;

class SchedulerRunner
{
    use Singleton, SchedulerRunnerTrait;

    protected RruleActionsLoader $actions_loader;
    protected RunnerActionsLoader $runner_jobs;
    protected array $runner_reccurences;

    private function __construct()
    {
        $this->actions_loader = RruleActionsLoader::getInstance();
        $this->runner_jobs = RunnerActionsLoader::getInstance();

        $this->runner_reccurences = (new SchedulerRunnerReccurences())::$intervals;

        // Rrule actions executor
        add_action("init", [$this, "actionsSchedulerJob"]);
        add_action("run_actions_scheduler_checking", [
            $this,
            "onRruleActionCall",
        ]);

        // Runner actions executor
        add_action("init", [$this, "runnerActionsJobs"]);
        add_action("runner_jobs_executor", [$this, "callRunnerJobs"]);
    }

    public function runnerActionsJobs(): void
    {
        if (
            function_exists("as_next_scheduled_action") &&
            !as_next_scheduled_action("runner_jobs_executor")
        ) {
            as_schedule_recurring_action(
                time(),
                $this->runner_reccurences["daily"]["interval"],
                "runner_jobs_executor",
                [],
                "",
                true
            );
        }
    }

    public function actionsSchedulerJob(): void
    {
        if (
            function_exists("as_next_scheduled_action") &&
            !as_next_scheduled_action("run_actions_scheduler_checking")
        ) {
            as_schedule_recurring_action(
                time(),
                $this->runner_reccurences["hourly"]["interval"],
                "run_actions_scheduler_checking",
                [],
                "",
                true
            );
        }
    }

    // jobs from Rrule >> Actions
    public function onRruleActionCall(): void
    {
        $series_for_current = (new RunnerController())->getSeriesForCurrentDateTime();

        if (!empty($series_for_current)) {
            foreach ($series_for_current as $series) {
                $action = !empty($series["action"]) ? $series["action"] : null;
                if (
                    !empty($action) &&
                    array_key_exists($action, $this->actions_loader->actions)
                ) {
                    $class_executor = $this->actions_loader->actions[$action];
                    $class_executor->onCall($series);
                } elseif ($action !== ActionsEnum::NONE->name) {
                    $make_com_report = new MakeComCli(
                        "https://hook.us1.make.com/ue8a3zi1zuo280vr461yn2fcj6ocrhe7",
                        [
                            "message" => print_r(
                                "\r Scheduler: please create an action for {$action} | \r this attached to the event with ID {$series["event_id"]} | \r and in task with ID {$series["task_id"]} | \r",
                                true
                            ),
                        ]
                    );
                    $make_com_report->sendRequest();
                }
            }
        }
    }

    // jobs from Runner >> Actions
    public function callRunnerJobs(): void
    {
        $series_next_three_days = (new RunnerController())->getSeriesForCurrentDateTime(
            modify: "+3 days"
        );
        if ($this->runner_jobs->actions) {
            foreach ($this->runner_jobs->actions as $runner_job) {
                if (
                    method_exists($runner_job, "getInstance") &&
                    method_exists($runner_job, "onCall")
                ) {
                    $runner_job::getInstance()->onCall($series_next_three_days);
                }
            }
        }
    }
}
