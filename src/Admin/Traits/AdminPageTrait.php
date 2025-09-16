<?php
namespace AM\Scheduler\Admin\Traits;

use AM\Scheduler\Utils\PluginMeta\Env;
use AM\Scheduler\Utils\Traits\Singleton;

trait AdminPageTrait
{
    use Singleton;

    protected string $slug;
    protected string $parent;
    protected string $pageTitle;
    protected string $menuName;
    protected float $position;

    private function __construct()
    {
        $this->position = 21.8312365;
        $this->parent = Env::getInstance()->path->getRootDirName();
        $this->slug = Env::getInstance()->path->getRootDirName();
        // loadersToInit
        add_action("admin_menu", [$this, "init"]);
    }

    public function init(): void {}

    public function setPageTitle(string $pageTitle): self
    {
        $this->pageTitle = $pageTitle;
        return $this;
    }
    public function setMenuName(string $menuName): self
    {
        $this->menuName = $menuName;
        return $this;
    }
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }
}
