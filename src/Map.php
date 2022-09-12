<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InsufficientInput;
use DobroSite\Mapping\Exception\InvalidSourceValue;

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

        if (!\array_key_exists($source, $this->map)) {
            throw new InsufficientInput(
                \sprintf(
                    'Key "%s" is not in the map. Available values are: %s.',
                    $source,
                    \implode(', ', \array_keys($this->map))
                )
            );
        }

        return $this->map[$source];
    }

    public function output(mixed $source): int | string
    {
        $key = \array_search($source, $this->map, true);
        if (\is_string($key) || \is_int($key)) {
            return $key;
        }

        throw new InvalidSourceValue(
            \sprintf(
                'Value %s is not in the map. Available values are: %s.',
                \var_export($source, true),
                \implode(
                    ', ',
                    \array_map(
                        static fn(mixed $value) => \var_export($value, true),
                        \array_values($this->map)
                    )
                )
            )
        );
    }
}
