<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class ArrayKeys implements Mapper
{
    public function __construct(
        private readonly Mapper $mapper,
    ) {
    }

    /**
     * @return array<string, mixed>
     *
     * @throws \DomainException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \UnexpectedValueException
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
     * @throws \DomainException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \UnexpectedValueException
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
