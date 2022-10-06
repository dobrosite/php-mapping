<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InvalidMapping;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Exception\InvalidSourceValue;

interface OutputMapper extends Mapper
{
    /**
     * @throws InvalidMapping
     * @throws InvalidSourceType
     * @throws InvalidSourceValue
     */
    public function output(mixed $source): mixed;
}
