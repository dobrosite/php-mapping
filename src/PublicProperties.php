<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class PublicProperties implements OutputMapper
{
    /**
     * @return array<string, mixed>
     */
    public function output(mixed $source): array
    {
        checkSourceType($this, 'output', ['object'], $source);
        \assert(\is_object($source));

        $object = new \ReflectionObject($source);

        $output = [];
        foreach ($object->getProperties() as $property) {
            $output[$property->getName()] = $property->getValue($source);
        }

        return $output;
    }
}
