<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class ArrayKeysMap extends ArrayKeys
{
    /**
     * @param array<string, string> $map
     */
    public function __construct(array $map)
    {
        parent::__construct(
            new Callback(
                input: fn($key) => \array_key_exists($key, $map) ? $map[$key] : $key,
                output: fn($key) => \array_search($key, $map, true) ?: $key,
            )
        );
    }
}
