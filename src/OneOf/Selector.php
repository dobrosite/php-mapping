<?php

declare(strict_types=1);

namespace DobroSite\Mapping\OneOf;

use DobroSite\Mapping\Type;

interface Selector
{
    public function getType(): Type;

    public function matches(mixed $dataValue): bool;
}
