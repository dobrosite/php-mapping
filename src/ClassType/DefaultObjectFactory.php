<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\Exception\ConfigurationError;

final class DefaultObjectFactory extends AbstractObjectFactory
{
    public function createObject(
        \ReflectionClass $class,
        Properties $properties,
        array $data
    ): object {
        $arguments = [];
        $constructor = $class->getConstructor();
        if ($constructor instanceof \ReflectionMethod) {
            if (!$constructor->isPublic()) {
                throw new ConfigurationError(
                    \sprintf(
                        '%s::%s is not public. Try another factory.',
                        $class->getName(),
                        $constructor->getName(),
                    )
                );
            }
            [$arguments, $data] = $this->getValues(
                $properties,
                $constructor->getParameters(),
                $data
            );
            \assert(\is_array($arguments));
            \assert(\is_array($data));
        }

        $object = $class->newInstanceArgs($arguments);
        \assert(\is_object($object));

        $this->setObjectProperties($object, $properties, $data);

        return $object;
    }
}
