<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InsufficientInput;
use DobroSite\Mapping\Exception\InvalidMapping;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Exception\InvalidSourceValue;

class ArrayKeys implements BidirectionalMapper
{
    public function __construct(
        private readonly BidirectionalMapper $mapper,
    ) {
    }

    /**
     * @return array<string, mixed>
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

        $mapped = [];

        foreach ($source as $key => $value) {
            $key = $this->mapper->input($key);
            \assert(\is_string($key));
            $mapped[$key] = $value;
        }

        return $mapped;
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

        $mapped = [];

        foreach ($source as $key => $value) {
            $key = $this->mapper->output($key);
            \assert(\is_string($key));
            $mapped[$key] = $value;
        }

        return $mapped;
    }
}
