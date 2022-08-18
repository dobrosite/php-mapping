<?php

declare(strict_types=1);

namespace Calculator\Prototype\Mapping;

use Calculator\Prototype\Exception\ConfigurationException;
use Calculator\Prototype\Exception\MappingException;
use Calculator\Prototype\Mapping\ClassName\TargetClassResolver;
use Calculator\Prototype\Mapping\Factory\DefaultObjectFactory;
use Calculator\Prototype\Mapping\Factory\ObjectFactory;
use DobroSite\Mapping\Type\Type;

class ClassMapping implements Type
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

    public function toPhpValue(mixed $dataValue): mixed
    {
        return $this->createObject($dataValue);
    }

    /**
     * @throws ConfigurationException
     * @throws MappingException
     */
    public function createObject(array $data): object
    {
        $class = $this->targetClassResolver->getTargetClass($data);

        return $this->factory->createObject($class, $this->properties, $data);
    }
}
