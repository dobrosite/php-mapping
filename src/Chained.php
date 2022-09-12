<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class Chained implements Mapper
{
    /**
     * @var Mapper[]
     */
    private readonly array $mappers;

    public function __construct(Mapper ...$mappers)
    {
        $this->mappers = $mappers;
    }

    public function input(mixed $source): mixed
    {
        foreach ($this->mappers as $mapper) {
            $source = $mapper->input($source);
        }

        return $source;
    }

    public function output(mixed $source): mixed
    {
        foreach (\array_reverse($this->mappers) as $mapper) {
            $source = $mapper->output($source);
        }

        return $source;
    }
}
