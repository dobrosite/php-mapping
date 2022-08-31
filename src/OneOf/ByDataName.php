<?php

declare(strict_types=1);

namespace DobroSite\Mapping\OneOf;

use DobroSite\Mapping\AbstractType;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\Type;

final class ByDataName extends AbstractType
{
    /**
     * @var array<string, Type>
     */
    private array $map;

    /**
     * @throws ConfigurationError
     */
    public function __construct(array $map)
    {
        \array_walk(
            $map,
            function (mixed $item, mixed $key): void {
                if (!\is_string($key)) {
                    throw new ConfigurationError(
                        \sprintf(
                            'Map keys should be a strings for %s, %s given.',
                            $this->getTypeName(),
                            \var_export($key, true)
                        )
                    );
                }
                if (!$item instanceof Type) {
                    throw new ConfigurationError(
                        \sprintf(
                            'Map values should be an instances of %s for %s, %s given for "%s".',
                            Type::class,
                            $this->getTypeName(),
                            \var_export($item, true),
                            $key
                        )
                    );
                }
            }
        );

        $this->map = $map;
    }

    public function toDataValue(mixed $phpValue): mixed
    {
        // TODO
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        if (!\is_array($dataValue)) {
            throw new DataError(
                \sprintf('Input data should be an array, %s given.', \var_export($dataValue, true))
            );
        }

        foreach ($this->map as $dataName => $type) {
            if (\array_key_exists($dataName, $dataValue)) {
                return $type->toPhpValue($dataValue);
            }
        }

        throw new DataError(
            \sprintf(
                'Input data should contain at least one of keys: "%s". Actual keys: "%s".',
                \implode('", "', \array_keys($this->map)),
                \implode('", "', \array_keys($dataValue)),
            )
        );
    }
}
