<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\ClassType\DefaultObjectFactory;
use DobroSite\Mapping\ClassType\ObjectFactory;
use DobroSite\Mapping\ClassType\Properties;
use DobroSite\Mapping\ClassType\Property;
use DobroSite\Mapping\ClassType\TargetClassResolver;
use DobroSite\Mapping\Data\DataSet;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;

class ClassType extends AbstractType
{
    protected readonly ObjectFactory $factory;

    public function __construct(
        protected readonly TargetClassResolver $targetClassResolver,
        public readonly Properties $properties,
        ?ObjectFactory $factory = null,
    ) {
        if ($factory === null) {
            $factory = new DefaultObjectFactory();
        }
        $this->factory = $factory;
    }

    /**
     * @return array<string, mixed>|null
     *
     * @throws ConfigurationError
     * @throws DataError
     */
    public function toDataValue(mixed $phpValue): ?array
    {
        if ($phpValue === null) {
            return null;
        }

        if (!\is_object($phpValue)) {
            throw new DataError(
                \sprintf(
                    'PHP value for ClassType should be an object, %s given.',
                    \gettype($phpValue)
                )
            );
        }

        $view = [];

        foreach ($this->properties as $property) {
            \assert($property instanceof Property);
            $value = $property->getValue($phpValue);
            try {
                $dataValue = $property->type->toDataValue($value);
            } catch (DataError $exception) {
                throw new ConfigurationError(
                    \sprintf(
                        'Cannot transform value %s of property "%s" to data value for "%s" field.',
                        formatValue($value),
                        $property->propertyName,
                        $property->dataName
                    ),
                    0,
                    $exception
                );
            }
            $view[$property->dataName] = $dataValue;
        }

        return $view;
    }

    /**
     * @throws \InvalidArgumentException
     * @throws ConfigurationError
     * @throws DataError
     */
    public function toPhpValue(mixed $dataValue): object
    {
        if (!\is_array($dataValue)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Only array values can be mapped on objects, %s given.',
                    \gettype($dataValue)
                )
            );
        }

        $class = $this->targetClassResolver->getTargetClass($dataValue);

        return $this->factory->createObject($class, $this->properties, new DataSet($dataValue));
    }
}
