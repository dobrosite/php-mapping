<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\DefaultValue;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;

abstract class AbstractObjectFactory implements ObjectFactory
{
    /**
     * @param array<\ReflectionParameter> $parameters
     * @param array<mixed> $data
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
            $valueNotDefined = true; // Чтобы отличать null от отсутствующего значения.
            $value = null;

            if (array_key_exists($property->dataName, $data)) {
                $value = $data[$property->dataName];
                unset($data[$property->dataName]);
                $valueNotDefined = false;
            } elseif ($property->defaultValue instanceof DefaultValue) {
                $value = $property->defaultValue->value;
                $valueNotDefined = false;
            }

            if ($valueNotDefined) {
                if ($parameter->isDefaultValueAvailable()) {
                    continue;
                }
                throw new DataError(
                    sprintf('No value is specified for the "%s" parameter.', $property->dataName)
                );
            }

            $value = $property->type->toPhpValue($value);
            $values[] = $value;
        }

        return [
            $values,
            $data,
        ];
    }

    /**
     * @param array<mixed> $data
     */
    protected function setObjectProperties(
        object $object,
        Properties $properties,
        array $data
    ): void {
        foreach ($data as $name => $value) {
            $property = $properties->findByDataName($name);
            $property->setValue($object, $value);
        }
    }
}
