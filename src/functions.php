<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InvalidSourceType;

/**
 * @param string[] $expected
 *
 * @throws InvalidSourceType
 */
function checkSourceType(Mapper $mapper, string $method, array $expected, mixed $given): void
{
    $type = \gettype($given);
    $type = match ($type) {
        'double' => 'float',
        default => $type,
    };

    if (!\in_array($type, $expected, true)) {
        throw new InvalidSourceType(
            \sprintf(
                'Argument for the %s::%s should be one of [%s], but %s given.',
                $mapper::class,
                $method,
                \implode(', ', $expected),
                formatValue($given)
            )
        );
    }
}

function formatValue(mixed $value): string
{
    if (\is_object($value)) {
        return 'instance of ' . $value::class;
    }

    return \var_export($value, true);
}
