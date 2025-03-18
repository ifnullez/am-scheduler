<?php

namespace MHS\Base\Interfaces;

interface ItemControllerInterface
{
    public function conditionalDisplay(array $request = []): void;
    public function list(): void;
    public function rootPageUrl(): string;
    public function delete(int $id): void;
    public function edit(int $id): void;
    public function new(): void;
    public function onSave(array $data = [], $return_item_id = false): ?int;
    public function getSidebar($item = null): void;
    public function getContent($item = null): void;
}
