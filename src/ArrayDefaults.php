<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InvalidSourceType;

class ArrayDefaults implements InputMapper
{
    /**
     * @param array<string, mixed> $defaults
     */
    public function __construct(
        private readonly array $defaults
    ) {
    }

    /**
     * @return array<string, mixed>
     *
     * @throws InvalidSourceType
     */
    public function input(mixed $source): array
    {
        checkSourceType($this, 'input', ['array'], $source);
        \assert(\is_array($source));

        return \array_merge($this->defaults, $source);
    }
}
