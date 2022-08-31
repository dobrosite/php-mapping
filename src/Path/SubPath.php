<?php

declare(strict_types=1);

namespace DobroSite\Mapping\Path;

use DobroSite\Mapping\AbstractType;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\Type;

use function DobroSite\Mapping\getByPath;

final class SubPath extends AbstractType
{
    public function __construct(
        private readonly string $path,
        private readonly Type $type,
    ) {
    }

    public function toDataValue(mixed $phpValue): mixed
    {
        return null; // FIXME
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        if (!\is_array($dataValue)) {
            throw new DataError(
                \sprintf(
                    '%s::toPhpValue expects an array, %s given.',
                    $this->getTypeName(),
                    \var_export($dataValue, true)
                )
            );
        }
        $dataValue = getByPath($this->path, $dataValue);

        return $this->type->toPhpValue($dataValue);
    }
}
