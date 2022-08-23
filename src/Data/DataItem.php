<?php

declare(strict_types=1);

namespace DobroSite\Mapping\Data;

final class DataItem
{
    public bool $used = false;

    public function __construct(
        public readonly mixed $value
    ) {
    }
}
