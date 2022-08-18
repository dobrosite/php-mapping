<?php

declare(strict_types=1);

namespace Calculator\Prototype\Mapping;

final class DefaultValue
{
    public function __construct(
        public readonly mixed $value
    ) {
    }
}
