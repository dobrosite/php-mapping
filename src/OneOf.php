<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\OneOf\Selector;

final class OneOf extends AbstractType
{
    /**
     * @var Selector[]
     */
    private readonly array $variants;

    public function __construct(Selector ...$variants)
    {
        $this->variants = $variants;
    }

    public function toDataValue(mixed $phpValue): mixed
    {
        return null; // FIXME
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        foreach ($this->variants as $variant) {
            if ($variant->matches($dataValue)) {
                return $variant->getType()->toPhpValue($dataValue);
            }
        }

        throw new DataError(
            \sprintf('Input data does not matches any of %s variants.', $this->getTypeName())
        );
    }
}
