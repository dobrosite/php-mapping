<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\Data;
use DobroSite\Mapping\Exception\ConfigurationError;

final class DefaultObjectFactory extends AbstractObjectFactory
{
    public function createObject(
        \ReflectionClass $class,
        Properties $properties,
        Data $data
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
            $arguments = $this->getValues($properties, $constructor->getParameters(), $data);
            \assert(\is_array($arguments));
        }

        $object = $class->newInstanceArgs($arguments);
        \assert(\is_object($object));

        $this->setObjectProperties($object, $properties, $data);

        return $object;
    }
}
