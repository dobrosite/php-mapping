<?php

declare(strict_types=1);

namespace Calculator\Prototype\Mapping\ClassName;

use Calculator\Prototype\Exception\ConfigurationException;
use Calculator\Prototype\Exception\MappingException;

interface TargetClassResolver
{
    /**
     * @throws ConfigurationException
     * @throws MappingException
     */
    public function getTargetClass(array $data): \ReflectionClass;
}
