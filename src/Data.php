<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

final class Data
{
    /**
     * @var array<DataItem>
     */
    private readonly array $items;

    /**
     * @param array<mixed> $items
     */
    public function __construct(array $items)
    {
        $this->items = \array_map(
            static fn(mixed $value): DataItem => new DataItem($value),
            $items
        );
    }

    public function get(string $name): ?DataItem
    {
        return $this->items[$name] ?? null;
    }
}
