<?php

/**
 * Path for the plugin
 */

namespace AM\Scheduler\Configs;

use AM\Scheduler\Base\Traits\Singleton;

class Path
{
    use Singleton;

    private ?string $root_file;
    private ?string $root_dir;
    private ?string $theme_dir;
    private ?string $root_url;
    private ?string $theme_url;

    private function __construct(?string $root_file, ?string $root_dir)
    {
        // plugin root file
        $this->root_file = $root_file;
        $this->root_dir = $root_dir;
        var_dump($this->root_dir);
        $this->theme_dir = get_stylesheet_directory();
        $this->root_url = plugin_dir_url($this->root_file);
        $this->theme_url = get_stylesheet_directory_uri();
    }

    public function getRootFile(): ?string
    {
        return $this->root_file;
    }

    public function getRootPath(): ?string
    {
        return $this->root_dir;
    }

    public function getThemePath(): ?string
    {
        return $this->theme_dir;
    }

    public function getThemeViewsPath(): string
    {
        return "{$this->theme_dir}/plugins/am/communities";
    }

    public function getSrcPath(): string
    {
        return "{$this->root_dir}/src";
    }

    public function getViewsPath(): string
    {
        return "{$this->root_dir}/views";
    }

    public function getAssetsPath(): string
    {
        return "{$this->root_dir}/assets";
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
        return "{$this->theme_url}/plugins/am/communities";
    }

    public function getAssetsUrl(): string
    {
        return "{$this->root_url}assets";
    }
}
