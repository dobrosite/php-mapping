<?php

declare(strict_types=1);

namespace DobroSite\Mapping\OneOf;

use DobroSite\Mapping\Type;

final class ByDiscriminator implements Selector
{
    public function __construct(
        private readonly string $dataName,
        private readonly string $value,
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

        return ($dataValue[$this->dataName] ?? null) === $this->value;
    }
}
