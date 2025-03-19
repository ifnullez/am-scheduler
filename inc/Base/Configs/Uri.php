<?php

/**
 * Path for the plugin
 */

namespace AM\Scheduler\Base\Configs;

use AM\Scheduler\Base\Traits\Singleton;

class Uri
{
    use Singleton;

    private ?string $root_file;
    private ?string $root_dir;

    private ?string $root_url;
    private ?string $theme_url;

    private function __construct(?string $root_file, ?string $root_dir)
    {
        // plugin root file
        $this->root_file = $root_file;
        $this->root_dir = $root_dir;

        $this->theme_url = get_stylesheet_directory_uri();
        $this->root_url = plugin_dir_url($this->root_file);
    }

    public function getAssetsUri(?string $asset): string
    {
        return esc_url("{$this->root_url}/assets/{$asset}");
    }

    public function getRootUrl(): ?string
    {
        return $this->root_url;
    }

    public function getThemeUrl(): ?string
    {
        return $this->theme_url;
    }

    public function getThemeViewsUrl(): string
    {
        return "{$this->theme_url}/plugins/am/scheduler";
    }

    public function getAssetsUrl(): string
    {
        return "{$this->root_url}assets";
    }
}
