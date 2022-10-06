<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Exception\InvalidSourceValue;

class EnumType implements BidirectionalMapper
{
    /**
     * @param class-string $enumType
     *
     * @throws InvalidSourceType Если $enumType не является именем перечисляемого типа.
     */
    public function __construct(
        private readonly string $enumType
    ) {
        try {
            new \ReflectionEnum($this->enumType);
        } catch (\ReflectionException) {
            throw new InvalidSourceType(
                \sprintf('%s is not an enum type.', $this->enumType)
            );
        }
    }

    public function input(mixed $source): mixed
    {
        checkSourceType($this, 'input', ['string'], $source);

        return $this->enumType::from($source);
    }

    public function output(mixed $source): mixed
    {
        if (!$source instanceof \BackedEnum) {
            throw new InvalidSourceType(
                \sprintf(
                    'Argument %s for the %s::output is not a valid enum case.',
                    formatValue($source),
                    $this::class
                )
            );
        }

        if (!$source instanceof $this->enumType) {
            throw new InvalidSourceValue(
                \sprintf(
                    'Value %s is not a case of %s.',
                    \var_export($source, true),
                    $this->enumType
                )
            );
        }

        return $source->value;
    }
}
