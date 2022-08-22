<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\DefaultValue;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\Exception\ValueNotSpecified;

abstract class AbstractObjectFactory implements ObjectFactory
{
    /**
     * @param array<string, mixed> $data
     *
     * @throws ConfigurationError
     * @throws DataError
     */
    protected function getValueFor(Property $property, array &$data): mixed
    {
        if (array_key_exists($property->dataName, $data)) {
            $value = $data[$property->dataName];
            unset($data[$property->dataName]);

            return $property->type->toPhpValue($value);
        }

        if ($property->defaultValue instanceof DefaultValue) {
            return $property->defaultValue->value;
        }

        throw ValueNotSpecified::create($property->dataName);
    }

    /**
     * @param array<\ReflectionParameter> $parameters
     * @param array<mixed>                $data
     *
     * @return array<mixed>
     *
     * @throws ConfigurationError
     * @throws DataError
     */
    protected function getValues(
        Properties $properties,
        array $parameters,
        array $data
    ): array {
        $values = [];

        foreach ($parameters as $parameter) {
            \assert($parameter instanceof \ReflectionParameter);

            $property = $properties->findByPropertyName($parameter->getName());

            try {
                $value = $this->getValueFor($property, $data);
            } catch (ValueNotSpecified $exception) {
                if ($parameter->isDefaultValueAvailable()) {
                    continue;
                }
                throw $exception;
            }

            $values[] = $value;
        }

        return [
            $values,
            $data,
        ];
    }

    /**
     * @param array<mixed> $data
     *
     * @throws ConfigurationError
     * @throws DataError
     */
    protected function setObjectProperties(
        object $object,
        Properties $properties,
        array &$data
    ): void {
        foreach ($properties as $property) {
            try {
                $value = $this->getValueFor($property, $data);
            } catch (ValueNotSpecified) {
                continue;
            }
            $property->setValue($object, $value);
        }
    }
}
