<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\Data\DataSet;
use DobroSite\Mapping\Data\DataItem;
use DobroSite\Mapping\DefaultValue;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\Exception\ValueAlreadyUsed;
use DobroSite\Mapping\Exception\ValueNotSpecified;

abstract class AbstractObjectFactory implements ObjectFactory
{
    public function createObject(
        \ReflectionClass $class,
        Properties $properties,
        DataSet $data
    ): object {
        foreach ($properties as $property) {
            if ($property->defaultValue instanceof DefaultValue) {
                $data->setDefaultValue($property->dataName, $property->defaultValue->value);
            }
        }

        $object = $this->createInstance($class, $properties, $data);

        $this->setObjectProperties($object, $properties, $data);

        return $object;
    }

    /**
     * @throws ConfigurationError
     * @throws DataError
     */
    abstract protected function createInstance(
        \ReflectionClass $class,
        Properties $properties,
        DataSet $data,
    ): object;

    /**
     * @throws ConfigurationError
     * @throws DataError
     */
    protected function setObjectProperties(
        object $object,
        Properties $properties,
        DataSet $data
    ): void {
        foreach ($properties as $property) {
            try {
                $dataItem = $this->useAsValueFor($property, $data);
            } catch (ValueNotSpecified|ValueAlreadyUsed) {
                continue;
            }
            $property->setValue($object, $property->type->toPhpValue($dataItem->value));
        }
    }

    /**
     * @param array<\ReflectionParameter> $parameters
     *
     * @return array<mixed>
     *
     * @throws ConfigurationError
     * @throws DataError
     */
    protected function useAsParameters(
        array $parameters,
        DataSet $data,
        Properties $properties,
    ): array {
        $values = [];

        foreach ($parameters as $parameter) {
            \assert($parameter instanceof \ReflectionParameter);

            $property = $properties->findByPropertyName($parameter->getName());

            try {
                $dataItem = $this->useAsValueFor($property, $data);
            } catch (ValueNotSpecified $exception) {
                if ($parameter->isDefaultValueAvailable()) {
                    continue;
                }
                throw $exception;
            }

            $values[] = $property->type->toPhpValue($dataItem->value);
        }

        return $values;
    }

    /**
     * @throws DataError
     * @throws ValueAlreadyUsed
     */
    protected function useAsValueFor(Property $property, DataSet $data): DataItem
    {
        $item = $data->get($property->dataName);
        if ($item instanceof DataItem) {
            if ($item->used) {
                throw new ValueAlreadyUsed(
                    \sprintf('Value for "%s" was already used.', $property->dataName)
                );
            }
            $item->used = true;

            return $item;
        }

        throw ValueNotSpecified::create($property->dataName);
    }
}
