<?php

namespace App\Modules\Shared\Domain;

final class OrderBy
{
    public const string ASC = 'ASC';
    public const string DESC = 'DESC';
    protected array $items;

    private function __construct(array ...$items)
    {
        $this->items = $items;

        foreach ($items as $item) {
            $field = $item[0] ?? null;
            if (!$field) {
                throw new \InvalidArgumentException('OrderBy requires a field name as the first element of each item.');
            }
            $criterion = $item[1] ?? null;
            if (self::ASC !== $criterion && self::DESC !== $criterion) {
                throw new \InvalidArgumentException('OrderBy requires a valid criterion (ASC or DESC) as the second element of each item.');
            }
        }
    }

    public static function asc(string $field): self
    {
        return new OrderBy([$field, self::ASC]);
    }

    public static function desc(string $field): self
    {
        return new OrderBy([$field, self::DESC]);
    }

    public static function fromValues(array|string $field, string $criterion = self::ASC): self
    {
        if (is_string($field)) {
            return new OrderBy([$field, $criterion ?: self::ASC]);
        }

        $orderings = [];

        foreach ($field as $f => $c) {
            $orderings[] = [$f, $c];
        }

        return new OrderBy(...$orderings);
    }

    public function toArray(): array
    {
        return $this->items;
    }
}
