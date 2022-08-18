<?php

declare(strict_types=1);

namespace Calculator\Prototype\Mapping\Factory;

use Calculator\Prototype\Exception\ConfigurationException;
use Calculator\Prototype\Exception\MappingException;
use Calculator\Prototype\Mapping\Properties;

interface ObjectFactory
{
    /**
     * @throws ConfigurationException
     * @throws MappingException
     */
    public function createObject(
        \ReflectionClass $class,
        Properties $properties,
        array $data
    ): ?object;
}
