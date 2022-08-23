<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\Data\DataSet;
use DobroSite\Mapping\Exception\ConfigurationError;

final class DefaultObjectFactory extends AbstractObjectFactory
{
    protected function createInstance(
        \ReflectionClass $class,
        Properties $properties,
        DataSet $data
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
            $arguments = $this->useAsParameters($constructor->getParameters(), $data, $properties);
            \assert(\is_array($arguments));
        }

        $object = $class->newInstanceArgs($arguments);
        \assert(\is_object($object));

        return $object;
    }
}
