<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\DataError;

class CollectionType extends AbstractType
{
    public function __construct(
        private readonly Type $itemType,
    ) {
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        if (!\is_array($dataValue)) {
            throw new DataError(
                \sprintf(
                    'Value for %s should be an array, but %s given.',
                    $this->getTypeName(),
                    \gettype($dataValue)
                )
            );
        }

        $items = [];
        foreach ($dataValue as $dataItem) {
            $items[] = $this->itemType->toPhpValue($dataItem);
        }

        return $items;
    }
}
