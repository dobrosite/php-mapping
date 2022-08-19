<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class EnumType implements Type
{
    public function __construct(
        private readonly string $enumType
    ) {
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        return $this->enumType::from($dataValue);
    }
}
