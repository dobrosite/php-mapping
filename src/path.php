<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\DataError;

/**
 * @throws DataError
 */
function getByPath(string $path, array $data): mixed
{
    $keys = \explode('.', $path);

    while ($keys !== []) {
        $key = \array_shift($keys);
        if (!\array_key_exists($key, $data)) {
            throw new DataError(
                \sprintf(
                    'Part "%s" of path "%s" does not exists.',
                    \implode('.', [$key, ...$keys]),
                    $path
                )
            );
        }
        $data = $data[$key];
    }

    return $data;
}
