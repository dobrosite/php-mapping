<?php

declare(strict_types=1);

namespace DobroSite\Mapping\Path;

use DobroSite\Mapping\AbstractType;
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
        // TODO
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        $dataValue = getByPath($this->path, $dataValue);

        return $this->type->toPhpValue($dataValue);
    }
}
