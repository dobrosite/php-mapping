<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\Data;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;

interface ObjectFactory
{
    /**
     * @throws ConfigurationError
     * @throws DataError
     */
    public function createObject(
        \ReflectionClass $class,
        Properties $properties,
        Data $data
    ): object;
}
