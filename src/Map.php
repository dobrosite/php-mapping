<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class Map implements Mapper
{
    /**
     * @param array<int|string, mixed> $map
     */
    public function __construct(
        private readonly array $map
    ) {
    }

    public function input(mixed $source): mixed
    {
        checkSourceType($this, 'input', ['integer', 'string'], $source);
        \assert(\is_int($source) || \is_string($source));

        if (\array_key_exists($source, $this->map)) {
            return $this->map[$source];
        }

        return $source;
    }

    public function output(mixed $source): int | string
    {
        $key = \array_search($source, $this->map, true);
        if (\is_string($key) || \is_int($key)) {
            return $key;
        }

        return $source;
    }
}
