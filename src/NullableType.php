<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class NullableType extends AbstractType
{
    public function __construct(
        private readonly Type $mainType,
    ) {
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        return $dataValue === null
            ? null
            : $this->mainType->toPhpValue($dataValue);
    }
}
