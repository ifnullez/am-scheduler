<?php

namespace AM\Scheduler\Utils\PluginMeta\Services;

use AM\Scheduler\Utils\PluginMeta\Env;
use AM\Scheduler\Utils\Traits\Singleton;

class Path
{
    use Singleton;

    private Env $env;
    private ?string $root_file;
    private ?string $root_dir;
    private ?string $theme_dir;

    private function __construct(Env $env)
    {
        // root env
        $this->env = $env;
        $this->root_file = $env->root_file;
        $this->root_dir = $env->root_dir;
        $this->theme_dir = get_stylesheet_directory();
    }

    public function getRootFile(): ?string
    {
        return $this->root_file;
    }

    public function getRootDirName(): ?string
    {
        return basename(rtrim($this->root_dir, "/"));
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
        return "{$this->theme_dir}/plugins/am/{$this->env->base_path}";
    }

    public function getSrcPath(): string
    {
        return "{$this->root_dir}/src";
    }

    public function getViewsPath(): string
    {
        return "{$this->root_dir}/views";
    }

    public function getAssetsPath(?string $asset): string
    {
        $assetsPathSlashes = !str_starts_with($asset, "/")
            ? "/{$asset}"
            : $asset;
        return "{$this->root_dir}/assets{$assetsPathSlashes}";
    }
}
