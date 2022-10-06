<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Exception\InvalidSourceValue;

class BooleanType implements BidirectionalMapper
{
    private readonly string $false;

    private readonly string $true;

    public function __construct(
        string $true = 'true',
        string $false = 'false',
    ) {
        $this->true = \mb_strtolower($true, 'utf8');
        $this->false = \mb_strtolower($false, 'utf8');
    }

    public function input(mixed $source): bool
    {
        if (!\is_scalar($source)) {
            throw new InvalidSourceType(
                \sprintf(
                    'Value for the %s should be a scalar, but %s given.',
                    __METHOD__,
                    formatValue($source)
                )
            );
        }

        return match (\mb_strtolower((string) $source, 'utf8')) {
            $this->true => true,
            $this->false => false,
            default => throw new InvalidSourceValue(
                \sprintf(
                    'Value "%s" is not allowed for the %s. Allowed values are "%s" and "%s".',
                    $source,
                    __METHOD__,
                    $this->true,
                    $this->false
                )
            ),
        };
    }

    public function output(mixed $source): string
    {
        checkSourceType($this, 'output', ['boolean'], $source);

        return match ((bool) $source) {
            true => $this->true,
            false => $this->false,
        };
    }
}
