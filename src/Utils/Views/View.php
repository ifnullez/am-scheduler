<?php

namespace AM\Scheduler\Utils\Views;

use AM\Scheduler\Utils\Traits\Singleton;
use AM\Scheduler\Utils\PluginMeta\Env;
use AM\Scheduler\Utils\Helpers\StrHelper;
use AM\Scheduler\Utils\Views\Enums\Screen;
use RuntimeException;

class View implements ViewInterface
{
    use Singleton;

    /**
     * Loads and renders a template file with provided data.
     *
     * @param string $templateFilePath Relative path to the template file.
     * @param array $data Variables to pass into the template.
     *
     * @throws RuntimeException If the template file doesn't exist.
     */
    public static function loadTemplate(
        string $templateFilePath,
        array $args = []
    ): void {
        $fullPath = self::resolveTemplatePath($templateFilePath);

        if (!file_exists($fullPath)) {
            throw new RuntimeException("Template file not found: {$fullPath}");
        }

        ob_start();

        require $fullPath;

        ob_get_clean();
    }

    /**
     * Resolves the full path to a template file.
     *
     * @param string $templateFilePath Relative path to the template.
     * @return string Absolute path to the template file.
     */
    private static function resolveTemplatePath(
        string $templateFilePath
    ): string {
        return Env::getInstance()->path->getViewsPath() . $templateFilePath;
    }

    /**
     * Returns the current admin screen.
     *
     * @return Screen|null
     */
    public static function getScreen(): ?Screen
    {
        if (empty($_GET["action"]) && empty(Screen::tryFrom($_GET["action"]))) {
            return Screen::LIST;
        }

        return Screen::tryFrom($_GET["action"]);
    }

    /**
     * Returns the dynamic admin screen title.
     *
     * @return string|null
     */
    public static function title(): ?string
    {
        $screen = self::getScreen();
        $pageTitle = esc_html(get_admin_page_title());

        if (!$screen || $screen === Screen::LIST) {
            return $pageTitle;
        }

        $actionTitle = (new StrHelper(esc_html($screen->name)))
            ->toLower()
            ->capitalize()
            ->get();

        $refinedTitle = (new StrHelper($pageTitle))->stripRight("s")->get();

        return "{$actionTitle} {$refinedTitle}";
    }
}
