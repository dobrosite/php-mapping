<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class ObjectConstructor extends ObjectMapper
{
    public function __construct(
        private readonly Mapper $class,
    ) {
    }

    protected function createInstance(array &$properties): object
    {
        $className = $this->detectClassName($properties);
        $class = $this->getClassReflection($className);

        $arguments = [];
        $constructor = $class->getConstructor();
        if ($constructor instanceof \ReflectionMethod) {
            if (!$constructor->isPublic()) {
                throw new \LogicException(
                    \sprintf(
                        '%s::%s is not public. Try another factory.',
                        $class->getName(),
                        $constructor->getName(),
                    )
                );
            }
            try {
                $arguments = $this->extractArgumentsForParameters(
                    $constructor->getParameters(),
                    $properties
                );
            } catch (\DomainException $exception) {
                throw new \DomainException(
                    \sprintf(
                        'Cannot call %s::__construct(). %s',
                        $className,
                        $exception->getMessage()
                    ),
                    0,
                    $exception
                );
            }
        }

        return $class->newInstance(...$arguments);
    }

    /**
     * @param array<string, mixed> $properties
     *
     * @throws \DomainException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \UnexpectedValueException
     */
    private function detectClassName(array $properties): string
    {
        $className = $this->class->input($properties);
        if (!\is_string($className)) {
            throw new \UnexpectedValueException(
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
