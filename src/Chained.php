<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class Chained implements BidirectionalMapper
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
            if ($mapper instanceof InputMapper) {
                $source = $mapper->input($source);
            }
        }

        return $source;
    }

    public function output(mixed $source): mixed
    {
        foreach (\array_reverse($this->mappers) as $mapper) {
            if ($mapper instanceof OutputMapper) {
                $source = $mapper->output($source);
            }
        }

        return $source;
    }
}
