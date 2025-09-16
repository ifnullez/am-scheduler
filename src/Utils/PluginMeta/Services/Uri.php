<?php

namespace AM\Scheduler\Utils\PluginMeta\Services;

use AM\Scheduler\Utils\PluginMeta\Env;
use AM\Scheduler\Utils\Traits\Singleton;

class Uri
{
    use Singleton;

    private Env $env;
    private ?string $root_file;
    private ?string $root_dir;

    private ?string $root_url;
    private ?string $theme_url;

    private function __construct(Env $env)
    {
        // root env
        $this->env = $env;
        // plugin root file
        $this->root_file = $this->env->root_file;
        $this->root_dir = $this->env->root_dir;

        $this->theme_url = get_stylesheet_directory_uri();
        $this->root_url = plugin_dir_url($this->env->root_file);
    }

    public function getAssetsUri(?string $asset): string
    {
        $assetsPathSlashes = !str_starts_with($asset, "/")
            ? "/{$asset}"
            : $asset;
        return esc_url("{$this->root_url}assets{$assetsPathSlashes}");
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
        return "{$this->theme_url}/plugins/am/{$this->env->base_path}";
    }

    public function getAssetsUrl(): string
    {
        return "{$this->root_url}assets";
    }
}
