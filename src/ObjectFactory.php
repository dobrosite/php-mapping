<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class ObjectFactory extends ObjectMapper
{
    /**
     * @var callable
     */
    private $factory;

    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    protected function createInstance(array &$properties): object
    {
        if (\is_array($this->factory)) {
            $parameters = $this->getParametersForArrayFactory($this->factory);
        } elseif (\is_string($this->factory) || $this->factory instanceof \Closure) {
            $parameters = $this->getParametersForFunction($this->factory);
        } else {
            throw new \LogicException(
                \sprintf('Unsupported factory type: %s.', \gettype($this->factory))
            );
        }

        $arguments = $this->extractArgumentsForParameters($parameters, $properties);

        $object = \call_user_func($this->factory, ...$arguments);
        \assert(\is_object($object));

        return $object;
    }

    /**
     * @param array<mixed>&callable $factory
     *
     * @return array<\ReflectionParameter>
     *
     * @throws \LogicException
     */
    private function getParametersForArrayFactory(array $factory): array
    {
        [$factoryClassOrObject, $factoryMethodName] = $factory;
        if (\is_object($factoryClassOrObject)) {
            $factoryReflection = new \ReflectionObject($factoryClassOrObject);
        } elseif (\is_string($factoryClassOrObject)) {
            try {
                $factoryReflection = new \ReflectionClass($factoryClassOrObject);
            } catch (\ReflectionException $exception) {
                throw new \LogicException(
                    \sprintf('Factory class "%s" not found.', $factoryClassOrObject),
                    0,
                    $exception
                );
            }
        } else {
            throw new \LogicException(
                \sprintf(
                    'Element 0 of callable array should be an object or a string, %s given.',
                    formatValue($factoryClassOrObject)
                )
            );
        }

        try {
            \assert(\is_string($factoryMethodName));
            $factoryMethod = $factoryReflection->getMethod($factoryMethodName);
        } catch (\ReflectionException $exception) {
            throw new \LogicException(
                \sprintf(
                    'Factory method "%s::%s" not found.',
                    $factoryReflection->getName(),
                    $factoryMethodName,
                ),
                0,
                $exception
            );
        }

        return $factoryMethod->getParameters();
    }

    /**
     * @return array<\ReflectionParameter>
     *
     * @throws \LogicException
     */
    private function getParametersForFunction(\Closure | string $factory): array
    {
        try {
            return (new \ReflectionFunction($factory))->getParameters();
        } catch (\ReflectionException $exception) {
            throw new \LogicException(
                \sprintf(
                    'Factory function "%s" not found.',
                    \is_string($factory) ? $factory : \gettype($factory)
                ),
                0,
                $exception
            );
        }
    }
}
