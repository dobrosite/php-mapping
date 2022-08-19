<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\DefaultValue;
use DobroSite\Mapping\SameType;
use DobroSite\Mapping\Type;

class Property
{
    public readonly string $dataName;

    public function __construct(
        public readonly string $propertyName,
        ?string $dataName = null,
        public readonly Type $type = new SameType(),
        public readonly ?DefaultValue $defaultValue = null,
    ) {
        $this->dataName = $dataName ?: $this->propertyName;
    }

    public function setValue(object $object, mixed $value): void
    {
        $object->{$this->propertyName} = $value;
    }
}
