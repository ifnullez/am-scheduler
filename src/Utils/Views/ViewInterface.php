<?php
namespace AM\Scheduler\Utils\Views;

use AM\Scheduler\Utils\Views\Enums\Screen;

interface ViewInterface
{
    /**
     * @param mixed $item
     */
    // public static function getSidebar(mixed $item = null): void;
    /**
     * @param mixed $item
     */
    // public static function getContent(mixed $item = null): void;
    public static function loadTemplate(
        string $templateFilePath,
        array $data = []
    ): void;
    public static function getScreen(): ?Screen;
    public static function title(): ?string;
}
