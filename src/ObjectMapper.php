<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InvalidMapping;

class ObjectMapper implements BidirectionalMapper
{
    public function __construct(
        private readonly InputMapper $input,
        private readonly OutputMapper $output = new PublicProperties(),
    ) {
    }

    public function input(mixed $source): object
    {
        checkSourceType($this, 'input', ['array'], $source);
        \assert(\is_array($source));

        $object = $this->input->input($source);
        if (!\is_object($object)) {
            throw new InvalidMapping(
                \sprintf(
                    '$input mapper for %s::__construct should return object, but it returned %s.',
                    $this::class,
                    \var_export($object, true)
                )
            );
        }
        $this->setProperties($object, $source);

        return $object;
    }

    public function output(mixed $source): mixed
    {
        checkSourceType($this, 'input', ['object'], $source);
        \assert(\is_object($source));

        return $this->output->output($source);
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setProperties(object $object, array $properties): void
    {
        $class = new \ReflectionObject($object);
        foreach ($class->getProperties() as $property) {
            if ($property->isReadOnly()) {
                continue;
            }
            if (!$property->isPublic()) {
                continue;
            }

            $name = $property->getName();
            if (!\array_key_exists($name, $properties)) {
                continue;
            }

            $property->setValue($object, $properties[$name]);
        }
    }
}
