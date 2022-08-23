<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\ClassType\DefaultObjectFactory;
use DobroSite\Mapping\ClassType\ObjectFactory;
use DobroSite\Mapping\ClassType\Properties;
use DobroSite\Mapping\ClassType\TargetClassResolver;
use DobroSite\Mapping\Data\DataSet;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;

class ClassType implements Type
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
