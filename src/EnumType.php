<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class EnumType implements Mapper
{
    /**
     * @param class-string $enumType
     *
     * @throws \InvalidArgumentException Если $enumType не является именем перечисляемого типа.
     */
    public function __construct(
        private readonly string $enumType
    ) {
        try {
            new \ReflectionEnum($this->enumType);
        } catch (\ReflectionException) {
            throw new \InvalidArgumentException(
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
            throw new \InvalidArgumentException(
                \sprintf(
                    'Argument %s for the %s::output is not a valid enum case.',
                    formatValue($source),
                    $this::class
                )
            );
        }

        if (!$source instanceof $this->enumType) {
            throw new \DomainException(
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
