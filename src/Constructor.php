<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InsufficientInput;
use DobroSite\Mapping\Exception\InvalidMapping;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Exception\InvalidSourceValue;

class Constructor extends AbstractObjectMapper
{
    private readonly InputMapper $class;

    public function __construct(InputMapper | string $class)
    {
        if (\is_string($class)) {
            $class = new Constant(input: $class);
        }
        $this->class = $class;
    }

    /**
     * @throws InsufficientInput
     * @throws InvalidSourceType
     * @throws InvalidMapping
     * @throws InvalidSourceValue
     */
    protected function createInstance(array &$properties): object
    {
        $className = $this->detectClassName($properties);
        $class = $this->getClassReflection($className);

        $arguments = [];
        $constructor = $class->getConstructor();
        if ($constructor instanceof \ReflectionMethod) {
            if (!$constructor->isPublic()) {
                throw new InvalidMapping(
                    \sprintf(
                        '%s::%s is not public. Try another factory.',
                        $class->getName(),
                        $constructor->getName(),
                    )
                );
            }
            $arguments = $this->extractArgumentsForParameters(
                $constructor->getParameters(),
                $properties
            );
        }

        return $class->newInstance(...$arguments);
    }

    /**
     * @param array<string, mixed> $properties
     *
     * @throws InsufficientInput
     * @throws InvalidMapping
     * @throws InvalidSourceType
     * @throws InvalidSourceValue
     */
    private function detectClassName(array $properties): string
    {
        $className = $this->class->input($properties);
        if (!\is_string($className)) {
            throw new InvalidMapping(
                \sprintf(
                    '%s cannot create object: class name should be a string, but %s returned by class name mapper.',
                    $this::class,
                    formatValue($className)
                )
            );
        }

        return $className;
    }
}
