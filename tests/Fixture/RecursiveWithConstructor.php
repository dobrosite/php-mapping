<?php

declare(strict_types=1);

namespace Tests\Fixture;

final class RecursiveWithConstructor
{
    public Recursive $child;

    public function __construct(Recursive $child)
    {
        $this->child = $child;
    }
}
