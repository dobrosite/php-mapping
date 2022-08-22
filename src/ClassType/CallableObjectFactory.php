<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;

final class CallableObjectFactory extends AbstractObjectFactory
{
    /**
     * @var callable
     */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @throws ConfigurationError
     * @throws DataError
     */
    public function createObject(
        \ReflectionClass $class,
        Properties $properties,
        array $data
    ): object {
        if (\is_array($this->callable)) {
            $parameters = self::getParametersForArrayFactory($this->callable);
        } else {
            $parameters = self::getParametersForFunction($this->callable);
        }

        [$arguments, $data] = $this->getValues($properties, $parameters, $data);
        \assert(\is_array($arguments));
        \assert(\is_array($data));

        $object = \call_user_func($this->callable, ...$arguments);
        \assert(\is_object($object));

        $this->setObjectProperties($object, $properties, $data);

        return $object;
    }

    /**
     * @param array<mixed>&callable $factory
     *
     * @return array<\ReflectionParameter>
     *
     * @throws ConfigurationError
     */
    private static function getParametersForArrayFactory(array $factory): array
    {
        [$factoryClassOrObject, $factoryMethodName] = $factory;
        if (\is_object($factoryClassOrObject)) {
            $factoryReflection = new \ReflectionObject($factoryClassOrObject);
        } elseif (\is_string($factoryClassOrObject)) {
            try {
                $factoryReflection = new \ReflectionClass($factoryClassOrObject);
            } catch (\ReflectionException $exception) {
                throw new ConfigurationError(
                    \sprintf('Factory class "%s" not found.', $factoryClassOrObject),
                    0,
                    $exception
                );
            }
        } else {
            throw new ConfigurationError(
                \sprintf(
                    'Element 0 of callable array should be an object or a string, %s given.',
                    \gettype($factoryClassOrObject)
                )
            );
        }

        try {
            \assert(\is_string($factoryMethodName));
            $factoryMethod = $factoryReflection->getMethod($factoryMethodName);
        } catch (\ReflectionException $exception) {
            throw new ConfigurationError(
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
     * @throws ConfigurationError
     */
    private static function getParametersForFunction(mixed $factory): array
    {
        try {
            return (new \ReflectionFunction($factory))->getParameters();
        } catch (\ReflectionException $exception) {
            throw new ConfigurationError(
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
