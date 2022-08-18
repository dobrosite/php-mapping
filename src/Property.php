<?php

declare(strict_types=1);

namespace Calculator\Prototype\Mapping;

use DobroSite\Mapping\Type\SameType;
use DobroSite\Mapping\Type\Type;

final class Property
{
    public function __construct(
        public readonly string $propertyName,
        public readonly ?string $dataName = null,
        public readonly Type $type = new SameType(),
        public readonly ?DefaultValue $defaultValue = null,
    ) {
    }
}
