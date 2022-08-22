<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\Data;
use DobroSite\Mapping\DataItem;
use DobroSite\Mapping\DefaultValue;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\Exception\ValueNotSpecified;

abstract class AbstractObjectFactory implements ObjectFactory
{
    /**
     * @throws DataError
     */
    protected function getDataItem(Property $property, Data $data): DataItem
    {
        $item = $data->get($property->dataName);
        if ($item instanceof DataItem) {
            return $item;
        }

        if ($property->defaultValue instanceof DefaultValue) {
            return new DataItem($property->defaultValue->value);
        }

        throw ValueNotSpecified::create($property->dataName);
    }

    /**
     * @param array<\ReflectionParameter> $parameters
     *
     * @return array<mixed>
     *
     * @throws ConfigurationError
     * @throws DataError
     */
    protected function getValues(
        Properties $properties,
        array $parameters,
        Data $data
    ): array {
        $values = [];

        foreach ($parameters as $parameter) {
            \assert($parameter instanceof \ReflectionParameter);

            $property = $properties->findByPropertyName($parameter->getName());

            try {
                $dataItem = $this->getDataItem($property, $data);
            } catch (ValueNotSpecified $exception) {
                if ($parameter->isDefaultValueAvailable()) {
                    continue;
                }
                throw $exception;
            }

            if (!$dataItem->used) {
                $values[] = $property->type->toPhpValue($dataItem->value);
                $dataItem->used = true;
            }
        }

        return $values;
    }

    /**
     * @throws ConfigurationError
     * @throws DataError
     */
    protected function setObjectProperties(
        object $object,
        Properties $properties,
        Data $data
    ): void {
        foreach ($properties as $property) {
            try {
                $dataItem = $this->getDataItem($property, $data);
            } catch (ValueNotSpecified) {
                continue;
            }
            if (!$dataItem->used) {
                $property->setValue($object, $property->type->toPhpValue($dataItem->value));
                $dataItem->used = true;
            }
        }
    }
}
