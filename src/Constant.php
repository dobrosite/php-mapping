<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class Constant implements Mapper
{
    public function __construct(
        private readonly mixed $input = null,
        private readonly mixed $output = null,
    ) {
    }

    public function input(mixed $source): mixed
    {
        return $this->input;
    }

    public function output(mixed $source): mixed
    {
        return $this->output;
    }
}
