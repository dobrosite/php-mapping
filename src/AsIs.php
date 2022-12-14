<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class AsIs implements BidirectionalMapper
{
    public function input(mixed $source): mixed
    {
        return $source;
    }

    public function output(mixed $source): mixed
    {
        return $source;
    }
}
