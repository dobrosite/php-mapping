<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InsufficientInput;
use DobroSite\Mapping\Exception\InvalidSourceType;

class ArrayValues implements BidirectionalMapper
{
    /**
     * @param array<string, Mapper> $mappers
     */
    public function __construct(
        private readonly array $mappers
    ) {
    }

    /**
     * @return array<string, mixed>
     *
     * @throws InsufficientInput
     * @throws InvalidSourceType
     */
    public function input(mixed $source): array
    {
        checkSourceType($this, 'input', ['array'], $source);
        \assert(\is_array($source));

        foreach ($this->mappers as $name => $mapper) {
            if (($mapper instanceof InputMapper) && \array_key_exists($name, $source)) {
                $source[$name] = $mapper->input($source[$name]);
            }
        }

        return $source;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws InvalidSourceType
     */
    public function output(mixed $source): array
    {
        checkSourceType($this, 'output', ['array'], $source);
        \assert(\is_array($source));

        foreach ($this->mappers as $name => $mapper) {
            if ($mapper instanceof OutputMapper) {
                $source[$name] = $mapper->output($source[$name]);
            }
        }

        return $source;
    }
}
