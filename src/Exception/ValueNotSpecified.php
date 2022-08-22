<?php

declare(strict_types=1);

namespace DobroSite\Mapping\Exception;

class ValueNotSpecified extends DataError
{
    public static function create(string $parameterName): self
    {
        return new self(sprintf('No value is specified for the "%s" parameter.', $parameterName));
    }
}
