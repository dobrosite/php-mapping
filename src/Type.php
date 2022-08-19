<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;

interface Type
{
    /**
     * @throws ConfigurationError
     * @throws DataError
     */
    public function toPhpValue(mixed $dataValue): mixed;
}
