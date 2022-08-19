<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\Exception\ConfigurationError;

final class ClassName implements TargetClassResolver
{
    public function __construct(
        private readonly string $class
    ) {
    }

    public function getTargetClass(array $data): \ReflectionClass
    {
        try {
            return new \ReflectionClass($this->class);
        } catch (\ReflectionException $exception) {
            throw new ConfigurationError(
                \sprintf('Cannot resolve class name: class "%s" not exist.', $this->class),
                0,
                $exception
            );
        }
    }
}
