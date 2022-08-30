<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;

class EnumType extends AbstractType
{
    /**
     * @param class-string $enumType
     *
     * @throws ConfigurationError Если $enumType не является именем перечисляемого типа.
     */
    public function __construct(
        private readonly string $enumType
    ) {
        try {
            new \ReflectionEnum($this->enumType);
        } catch (\ReflectionException) {
            throw new ConfigurationError(\sprintf('%s is not an enum type.', $this->enumType));
        }
    }

    public function toDataValue(mixed $phpValue): mixed
    {
        if (!$phpValue instanceof \BackedEnum) {
            throw new DataError(
                \sprintf('Value %s is not a valid enum case.', \var_export($phpValue, true))
            );
        }

        if (!$phpValue instanceof $this->enumType) {
            throw new DataError(
                \sprintf(
                    'Value %s is not a case of %s.',
                    \var_export($phpValue, true),
                    $this->enumType
                )
            );
        }

        return $phpValue->value;
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        return $this->enumType::from($dataValue);
    }
}
