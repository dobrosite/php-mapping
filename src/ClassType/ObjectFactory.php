<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;

interface ObjectFactory
{
    /**
     * @param array<mixed> $data
     *
     * @throws ConfigurationError
     * @throws DataError
     */
    public function createObject(
        \ReflectionClass $class,
        Properties $properties,
        array $data
    ): object;
}
