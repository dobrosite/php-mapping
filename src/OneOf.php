<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\OneOf\Selector;

final class OneOf extends AbstractType
{
    /**
     * @var Selector[]
     */
    private array $variants;

    public function __construct(Selector ...$variants)
    {
        $this->variants = $variants;
    }

    public function toDataValue(mixed $phpValue): mixed
    {
        // TODO
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        foreach ($this->variants as $variant) {
            if ($variant->matches($dataValue)) {
                return $variant->getType()->toPhpValue($dataValue);
            }
        }
    }
}
