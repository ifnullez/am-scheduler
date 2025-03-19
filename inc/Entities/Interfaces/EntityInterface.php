<?php
namespace AM\Scheduler\Entities\Interfaces;

interface EntityInterface
{
    // public static function insert(array $keys, array $values): ?bool;
    public function up(): ?bool;
    public function down(): ?bool;
    public function schema(): string;
    public function updateSchema(): ?array;
    public function __get(string $property): mixed;
    // public function __set(string $property, mixed $value): void;
}
