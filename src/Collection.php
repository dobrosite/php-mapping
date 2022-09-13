<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InsufficientInput;
use DobroSite\Mapping\Exception\InvalidMapping;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Exception\InvalidSourceValue;

class Collection implements Mapper
{
    public function __construct(
        private readonly Mapper $mapper
    ) {
    }

    /**
     * @return array<int, mixed>
     *
     * @throws InsufficientInput
     * @throws InvalidMapping
     * @throws InvalidSourceType
     * @throws InvalidSourceValue
     */
    public function input(mixed $source): array
    {
        checkSourceType($this, 'input', ['array'], $source);
        \assert(\is_array($source));

        foreach ($source as &$item) {
            $item = $this->mapper->input($item);
        }

        return $source;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws InvalidMapping
     * @throws InvalidSourceType
     * @throws InvalidSourceValue
     */
    public function output(mixed $source): array
    {
        checkSourceType($this, 'output', ['array'], $source);
        \assert(\is_array($source));

        foreach ($source as &$item) {
            $item = $this->mapper->output($item);
        }

        return $source;
    }
}
