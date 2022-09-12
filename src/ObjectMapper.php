<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InsufficientInput;
use DobroSite\Mapping\Exception\InvalidMapping;
use DobroSite\Mapping\Exception\InvalidSourceType;

abstract class ObjectMapper implements Mapper
{
    public function input(mixed $source): object
    {
        checkSourceType($this, 'input', ['array'], $source);

        $object = $this->createInstance($source);
        $this->setProperties($object, $source);

        return $object;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws InvalidSourceType
     */
    public function output(mixed $source): array
    {
        checkSourceType($this, 'input', ['object'], $source);
        \assert(\is_object($source));

        $object = new \ReflectionObject($source);

        $output = [];
        foreach ($object->getProperties() as $property) {
            $output[$property->getName()] = $property->getValue($source);
        }

        return $output;
    }

    /**
     * @param array<string, mixed> $properties
     *
     * @throws InvalidSourceType
     * @throws InsufficientInput
     */
    abstract protected function createInstance(array &$properties): object;

    /**
     * @param \ReflectionParameter[] $parameters
     * @param array<string, mixed>   $properties
     *
     * @return array<string, mixed>
     *
     * @throws InsufficientInput
     */
    protected function extractArgumentsForParameters(
        array $parameters,
        array &$properties
    ): array {
        $arguments = [];

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            if (!\array_key_exists($name, $properties)) {
                throw new InsufficientInput(
                    \sprintf('There is no "%s" field in the input data.', $name)
                );
            }
            $arguments[$name] = $properties[$name];
            unset($properties[$name]);
        }

        return $arguments;
    }

    /**
     * @throws InvalidMapping
     */
    protected function getClassReflection(string $className): \ReflectionClass
    {
        try {
            $class = new \ReflectionClass($className);
        } catch (\ReflectionException $exception) {
            throw new InvalidMapping(
                \sprintf(
                    '%s cannot create object. %s',
                    $this::class,
                    $exception->getMessage()
                ),
                0,
                $exception
            );
        }

        return $class;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setProperties(object $object, array $properties): void
    {
        $class = new \ReflectionObject($object);
        foreach ($class->getProperties() as $property) {
            $name = $property->getName();
            if (\array_key_exists($name, $properties)) {
                $property->setValue($object, $properties[$name]);
            }
        }
    }
}
