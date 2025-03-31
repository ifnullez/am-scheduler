<?php
namespace AM\Scheduler\Base\Views;

interface ViewsInterface
{
    /**
     * @param mixed $item
     */
    public static function getSidebar(mixed $item = null): void;
    /**
     * @param mixed $item
     */
    public static function getContent(mixed $item = null): void;
}
