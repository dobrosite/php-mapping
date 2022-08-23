<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\DataError;

class BooleanType implements Type
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

    public function toPhpValue(mixed $dataValue): bool
    {
        if (!\is_scalar($dataValue)) {
            throw new DataError(
                \sprintf(
                    'Value for the BooleanType should be a scalar, but %s given.',
                    \gettype($dataValue)
                )
            );
        }

        return match (\mb_strtolower((string) $dataValue, 'utf8')) {
            $this->true => true,
            $this->false => false,
            default => throw new DataError(
                \sprintf(
                    'Value "%s" is not allowed for the BooleanType. Allowed values are "%s" and "%s".',
                    $dataValue,
                    $this->true,
                    $this->false
                )
            ),
        };
    }
}
