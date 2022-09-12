<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class ArrayValues implements Mapper
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
     * @throws \InvalidArgumentException
     */
    public function input(mixed $source): array
    {
        checkSourceType($this, 'input', ['array'], $source);
        \assert(\is_array($source));

        foreach ($this->mappers as $name => $mapper) {
            $source[$name] = $mapper->input($source[$name]);
        }

        return $source;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws \InvalidArgumentException
     */
    public function output(mixed $source): array
    {
        checkSourceType($this, 'output', ['array'], $source);
        \assert(\is_array($source));

        foreach ($this->mappers as $name => $mapper) {
            $source[$name] = $mapper->output($source[$name]);
        }

        return $source;
    }
}
