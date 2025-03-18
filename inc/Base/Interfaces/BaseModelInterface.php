<?php

namespace MHS\Base\Interfaces;

interface BaseModelInterface
{
    public function getAll(): ?array;
    public function findBy(string $key, string $value): ?array;
    public function findWhere(array $fields, string $search): ?array;
    public function __toArray(array $array = []): ?array;
    public function delete(?int $id): ?bool;
}
