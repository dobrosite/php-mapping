<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class CustomType extends AbstractType
{
    /**
     * @var callable
     */
    private $toPhpValue;

    public function __construct(
        callable $toPhpValue,
    ) {
        $this->toPhpValue = $toPhpValue;
    }

    public function toPhpValue(mixed $dataValue): mixed
    {
        return \call_user_func($this->toPhpValue, $dataValue);
    }
}
