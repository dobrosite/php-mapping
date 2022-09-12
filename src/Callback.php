<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class Callback implements Mapper
{
    protected mixed $input;

    protected mixed $output;

    public function __construct(callable $input = null, callable $output = null)
    {
        $this->input = $input ?: static fn($source) => $source;
        $this->output = $output ?: static fn($source) => $source;
    }

    public function input(mixed $source): mixed
    {
        \assert(\is_callable($this->input));

        return \call_user_func($this->input, $source);
    }

    public function output(mixed $source): mixed
    {
        \assert(\is_callable($this->output));

        return \call_user_func($this->output, $source);
    }
}
