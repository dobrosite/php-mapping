<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

abstract class AbstractType implements Type
{
    public function getTypeName(): string
    {
        return basename(str_replace('\\', '/', $this::class));
    }
}
