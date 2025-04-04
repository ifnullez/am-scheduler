<?php
namespace AM\Scheduler\Base\Contracts\Interfaces;

interface ContractInterface
{
    // public static function insert(array $keys, array $values): ?bool;
    public function up(): ?bool;
    public function down(): ?bool;
    public function schema(): string;
    public function update(): ?array;
    public function __get(string $property): mixed;
    // public function __set(string $property, mixed $value): void;
}
