<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\DefaultValue;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\SameType;
use DobroSite\Mapping\Type;

class Property
{
    public readonly string $dataName;

    public function __construct(
        public readonly string $propertyName,
        ?string $dataName = null,
        public readonly Type $type = new SameType(),
        public readonly ?DefaultValue $defaultValue = null,
    ) {
        $this->dataName = $dataName ?: $this->propertyName;
    }

    /**
     * @throws ConfigurationError
     */
    public function setValue(object $object, mixed $value): void
    {
        try {
            $reflection = new \ReflectionProperty($object, $this->propertyName);
        } catch (\ReflectionException $exception) {
            throw new ConfigurationError(
                \sprintf(
                    'Object of class %s does not has property "%s".',
                    $object::class,
                    $this->propertyName
                ),
                previous: $exception
            );
        }

        if ($reflection->isReadOnly()) {
            return;
        }

        $reflection->setValue($object, $value);
    }
}
