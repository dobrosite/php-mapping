<?php

declare(strict_types=1);

namespace Calculator\Prototype\Mapping\Factory;

use Calculator\Prototype\Exception\ConfigurationException;
use Calculator\Prototype\Exception\MappingException;
use Calculator\Prototype\Mapping\DefaultValue;
use Calculator\Prototype\Mapping\Properties;

final class CallableObjectFactory implements ObjectFactory
{
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function createObject(
        \ReflectionClass $class,
        Properties $properties,
        array $data
    ): ?object {
        if (\is_array($this->callable)) {
            $parameters = self::getParametersForArrayFactory($this->callable);
        } else {
            // FIXME $parameters = self::getParametersForFunction($factory, $class);
        }

        $arguments = [];
        foreach ($parameters as $parameter) {
            \assert($parameter instanceof \ReflectionParameter);

            $property = $properties->findByPropertyName($parameter->getName());
            $valueNotDefined = true; // Чтобы отличать null от отсутствующего значения.
            $value = null;

            if (array_key_exists($property->dataName, $data)) {
                $value = $data[$property->dataName];
                $valueNotDefined = false;
            } elseif ($property->defaultValue instanceof DefaultValue) {
                $value = $property->defaultValue->value;
                $valueNotDefined = false;
            }

            if ($valueNotDefined) {
                if ($parameter->isDefaultValueAvailable()) {
                    continue;
                }
                throw new MappingException(
                    sprintf(
                        'Не указан параметр «%s» для создания экземпляра «%s».',
                        $property->dataName,
                        $class->getName(),
                    )
                );
            }

            $value = $property->type->toPhpValue($value);
            $arguments[] = $value;
        }

        $factory = $this->callable;

        return $factory(...$arguments);
    }

    /**
     * @return array<\ReflectionParameter>
     *
     * @throws ConfigurationException
     */
    private static function getParametersForArrayFactory(array $factory): array
    {
        [$factoryClassName, $factoryMethodName] = $factory;
        try {
            $factoryClass = new \ReflectionClass($factoryClassName);
        } catch (\ReflectionException $exception) {
            throw new ConfigurationException(
                \sprintf('Factory class "%s" not found.', $factoryClassName),
                0,
                $exception
            );
        }

        try {
            $factoryMethod = $factoryClass->getMethod($factoryMethodName);
        } catch (\ReflectionException $exception) {
            throw new ConfigurationException(
                \sprintf(
                    'Factory method "%s::%s" not found.',
                    $factoryClass->getName(),
                    $factoryMethodName,
                ),
                0,
                $exception
            );
        }

        return $factoryMethod->getParameters();
    }
}
