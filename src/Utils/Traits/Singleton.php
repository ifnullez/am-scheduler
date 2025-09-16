<?php

namespace AM\Scheduler\Utils\Traits;

trait Singleton
{
    private static $instance;

    public static function getInstance(mixed ...$args)
    {
        if (!self::$instance) {
            self::$instance = new self(...$args);
        }
        return self::$instance;
    }

    private function __construct() {}
    /**
     * @return void
     */
    private function __clone() {}
    /**
     * @return void
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton class");
    }
}
