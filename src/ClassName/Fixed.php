<?php

declare(strict_types=1);

namespace Calculator\Prototype\Mapping\ClassName;

final class Fixed implements TargetClassResolver
{
    public function __construct(
        private readonly string $class
    ) {
    }

    public function getTargetClass(array $data): \ReflectionClass
    {
        return new \ReflectionClass($this->class);
    }
}
