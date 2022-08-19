<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class DefaultValue
{
    public function __construct(
        public readonly mixed $value
    ) {
    }
}
