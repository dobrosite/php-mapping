<?php

declare(strict_types=1);

namespace DobroSite\Mapping\Type;

final class SameType implements Type
{
    public function toPhpValue(mixed $dataValue): mixed
    {
        return $dataValue;
    }
}
