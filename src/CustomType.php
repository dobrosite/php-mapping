<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class CustomType extends AbstractType
{
    /**
     * @var callable
     */
    private $toDataValue;

    /**
     * @var callable
     */
    private $toPhpValue;

    public function __construct(
        callable $toPhpValue,
        callable $toDataValue,
    ) {
        $this->toPhpValue = $toPhpValue;
        $this->toDataValue = $toDataValue;
    }

    public function toDataValue(mixed $phpValue): mixed
    {
        return \call_user_func($this->toDataValue, $phpValue);
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        return \call_user_func($this->toPhpValue, $dataValue);
    }
}
