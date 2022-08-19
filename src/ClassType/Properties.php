<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

class Properties
{
    /**
     * @var array<Property>
     */
    private array $properties = [];

    public function __construct(Property ...$properties)
    {
        $this->add(...$properties);
    }

    public function add(Property ...$properties): self
    {
        $this->properties = [...$this->properties, ...$properties];

        return $this;
    }

    public function findByDataName(string $name): Property
    {
        foreach ($this->properties as $property) {
            \assert($property instanceof Property);
            if ($property->dataName === $name) {
                return $property;
            }
        }

        return new Property($name, $name);
    }

    public function findByPropertyName(string $name): Property
    {
        foreach ($this->properties as $property) {
            \assert($property instanceof Property);
            if ($property->propertyName === $name) {
                return $property;
            }
        }

        return new Property($name, $name);
    }
}
