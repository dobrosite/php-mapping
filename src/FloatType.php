<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\DataError;

final class FloatType implements Type
{
    private \NumberFormatter $formatter;

    public function __construct(
        \NumberFormatter $formatter = null
    ) {
        $this->formatter = $formatter ?: new \NumberFormatter(
            \Locale::getDefault(),
            \NumberFormatter::DEFAULT_STYLE
        );
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        if (\is_int($dataValue) || \is_float($dataValue)) {
            return $dataValue;
        }

        if (!\is_string($dataValue)) {
            throw new DataError(
                \sprintf(
                    'Value for FloatType should be either integer, float or string, but %s given.',
                    \gettype($dataValue)
                )
            );
        }

        return $this->formatter->parse($dataValue);
    }
}
