<?php

declare(strict_types=1);

namespace Calculator\Prototype\Mapping\Factory;

use Calculator\Prototype\Exception\ConfigurationException;
use Calculator\Prototype\Mapping\Properties;

final class DefaultObjectFactory implements ObjectFactory
{
    public function createObject(
        \ReflectionClass $class,
        Properties $properties,
        array $data
    ): ?object {
        $constructor = $class->getConstructor();
        if ($constructor instanceof \ReflectionMethod) {
            if (!$constructor->isPublic()) {
                throw new ConfigurationException(
                    \sprintf(
                        '%s::%s is not public. Try to use %s.',
                        $class->getName(),
                        $constructor->getName(),
                        CallableObjectFactory::class
                    )
                );
            }
        }

        return $class->newInstanceArgs($data);
    }
}
