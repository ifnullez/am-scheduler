<?php

namespace MHS\Base\Traits;

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

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton class");
    }
}
