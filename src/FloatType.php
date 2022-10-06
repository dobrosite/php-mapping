<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class FloatType implements BidirectionalMapper
{
    private readonly \NumberFormatter $formatter;

    public function __construct(
        \NumberFormatter $formatter = null
    ) {
        $this->formatter = $formatter ?: new \NumberFormatter(
            \Locale::getDefault(),
            \NumberFormatter::DEFAULT_STYLE
        );
    }

    public function input(mixed $source): float
    {
        checkSourceType($this, 'input', ['float', 'integer', 'string'], $source);
        \assert(\is_float($source) || \is_int($source) || \is_string($source));

        if (\is_string($source)) {
            return (float) $this->formatter->parse($source);
        }

        return (float) $source;
    }

    public function output(mixed $source): string
    {
        checkSourceType($this, 'output', ['float', 'integer'], $source);
        \assert(\is_float($source) || \is_int($source));

        return (string) $this->formatter->format($source);
    }
}
