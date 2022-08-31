<?php

declare(strict_types=1);

namespace DobroSite\Mapping\OneOf;

use DobroSite\Mapping\Type;

final class ByExistedField implements Selector
{
    public function __construct(
        private readonly string $dataName,
        private readonly Type $type,
    ) {
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function matches(mixed $dataValue): bool
    {
        if (!\is_array($dataValue)) {
            return false;
        }

        return \array_key_exists($this->dataName, $dataValue);
    }
}
