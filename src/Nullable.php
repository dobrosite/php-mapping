<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class Nullable implements Mapper
{
    public function __construct(
        private readonly Mapper $mapper,
    ) {
    }

    public function input(mixed $source): mixed
    {
        return $source === null
            ? null
            : $this->mapper->input($source);
    }

    public function output(mixed $source): mixed
    {
        return $source === null
            ? null
            : $this->mapper->output($source);
    }
}
