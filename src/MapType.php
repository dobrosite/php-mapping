<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\DataError;

class MapType extends AbstractType
{
    /**
     * @param array<int|string, mixed> $map
     */
    public function __construct(
        private readonly array $map
    ) {
    }

    public function toDataValue(mixed $phpValue): int | string
    {
        $key = \array_search($phpValue, $this->map, true);
        if (\is_string($key) || \is_int($key)) {
            return $key;
        }

        throw new DataError(
            \sprintf(
                'Value %s is not in the map. Available values are: %s.',
                \var_export($phpValue, true),
                \implode(
                    ', ',
                    \array_map(
                        static fn(mixed $value) => \var_export($value, true),
                        \array_values($this->map)
                    )
                )
            )
        );
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        if (!\is_int($dataValue) && !\is_string($dataValue)) {
            throw new DataError(
                \sprintf(
                    '%s supports string and int values only, %s given.',
                    $this->getTypeName(),
                    \gettype($dataValue)
                )
            );
        }

        if (!\array_key_exists($dataValue, $this->map)) {
            throw new DataError(
                \sprintf(
                    'Value "%s" is not in the map. Available values are: %s.',
                    $dataValue,
                    \implode(', ', \array_keys($this->map))
                )
            );
        }

        return $this->map[$dataValue];
    }
}
