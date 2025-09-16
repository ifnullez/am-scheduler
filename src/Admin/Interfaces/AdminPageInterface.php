<?php
namespace AM\Scheduler\Admin\Interfaces;

interface AdminPageInterface
{
    public function callback(): void;
    public function init(): void;
    public function setMenuName(string $plural): self;
    public function setPageTitle(string $singular): self;
    public function setSlug(string $slug): self;
}
