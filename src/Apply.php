<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InvalidMapping;

class Apply implements BidirectionalMapper
{
    private readonly InputMapper $input;

    private readonly OutputMapper $output;

    public function __construct(InputMapper $input = null, OutputMapper $output = null)
    {
        $this->input = $input ?: new AsIs();
        $this->output = $output ?: new AsIs();
    }

    public function input(mixed $source): mixed
    {
        $mapper = $this->input->input($source);
        if (!$mapper instanceof Mapper) {
            throw new InvalidMapping(
                \sprintf(
                    'Mapper passed to %s::__construct should return instance of %s, but it returned %s.',
                    $this::class,
                    Mapper::class,
                    formatValue($mapper)
                )
            );
        }

        return $mapper->input($source);
    }

    public function output(mixed $source): mixed
    {
        $mapper = $this->output->output($source);
        if (!$mapper instanceof Mapper) {
            throw new InvalidMapping(
                \sprintf(
                    'Mapper passed to %s::__construct should return instance of %s, but it returned %s.',
                    $this::class,
                    Mapper::class,
                    formatValue($mapper)
                )
            );
        }

        return $mapper->output($source);
    }
}
