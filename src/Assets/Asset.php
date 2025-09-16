<?php

namespace AM\Scheduler\Assets;

use AM\Scheduler\Utils\Traits\{GetterTrait, SetterTrait};
use AM\Scheduler\Utils\PluginMeta\Env;
use AM\Scheduler\Utils\Helpers\StrHelper;
use Exception;

class Asset
{
    use GetterTrait, SetterTrait;

    private readonly string $name;
    private readonly string $type;
    private readonly string $uri;
    private readonly string $path;
    private readonly string $version;
    private readonly ?array $file;

    private string $i18nObjectName;
    private array $dependencies = [];
    private array $localize = [];
    private bool $inFooter = true;
    private bool $isRegistered = false;

    public function __construct(string $relativePath)
    {
        $env = Env::getInstance();

        $this->path = $env->path->getAssetsPath($relativePath);
        $this->uri = $env->uri->getAssetsUri($relativePath);
        $this->version = $env->getVersion();
        $this->file = pathinfo($this->path);

        if (!file_exists($this->path)) {
            throw new Exception(
                "Asset file does not exist at path: {$this->path}"
            );
        }

        $this->type = $this->file["extension"];
        $this->name = $this->file["filename"];
        $this->i18nObjectName = (new StrHelper($this->name))
            ->toCamelCase()
            ->append("I18n")
            ->get();
    }

    public function setInFooter(?bool $inFooter = true): self
    {
        $this->inFooter = $inFooter;
        return $this;
    }

    public function setI18nObjectName(?string $i18nObjectName): self
    {
        $this->i18nObjectName = $i18nObjectName;

        return $this;
    }

    public function setDependencies(?array $dependencies = []): self
    {
        $this->dependencies = $dependencies;
        return $this;
    }

    public function localize(array $localizationData = []): self
    {
        if ($this->type !== "js") {
            throw new Exception(
                "Localization is only supported for JavaScript files."
            );
        }

        $this->localize = $localizationData;

        $this->register();

        wp_localize_script($this->name, $this->i18nObjectName, $this->localize);

        return $this;
    }

    public function load(): void
    {
        if ($this->type === "js") {
            if (!empty($this->localize)) {
                // Already registered during localization
                wp_enqueue_script($this->name);
            } else {
                wp_enqueue_script(
                    $this->name,
                    $this->uri,
                    $this->dependencies,
                    $this->version,
                    $this->inFooter
                );
            }
        } elseif ($this->type === "css") {
            wp_enqueue_style(
                $this->name,
                $this->uri,
                $this->dependencies,
                $this->version
            );
        }
    }

    private function register(): void
    {
        if ($this->isRegistered) {
            return;
        }

        wp_register_script(
            $this->name,
            $this->uri,
            $this->dependencies,
            $this->version,
            $this->inFooter
        );

        $this->isRegistered = true;
    }
}
