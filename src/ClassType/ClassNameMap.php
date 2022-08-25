<?php

declare(strict_types=1);

namespace DobroSite\Mapping\ClassType;

use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;

final class ClassNameMap implements TargetClassResolver
{
    /**
     * @param array<string, string> $map
     */
    public function __construct(
        private readonly string $dataName,
        private readonly array $map,
    ) {
    }

    public function getTargetClass(array $data): \ReflectionClass
    {
        if (!\array_key_exists($this->dataName, $data)) {
            throw new DataError(
                \sprintf(
                    'Cannot resolve class name: parameter "%s" not specified.',
                    $this->dataName
                )
            );
        }

        $value = $data[$this->dataName];

        if (!\array_key_exists($value, $this->map)) {
            throw new DataError(
                \sprintf(
                    'Cannot resolve class name: unknown value "%s" of "%s". Acceptable values are: %s.',
                    $value,
                    $this->dataName,
                    \implode(', ', \array_keys($this->map))
                )
            );
        }
        $className = $this->map[$value];

        try {
            return new \ReflectionClass($className);
        } catch (\ReflectionException $exception) {
            throw new ConfigurationError(
                \sprintf('Cannot resolve class name: class "%s" not exist.', $className),
                0,
                $exception
            );
        }
    }
}
