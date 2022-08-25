<?php

declare(strict_types=1);

namespace Tests\Fixture;

final class RecursiveWithConstructor
{
    public function __construct(public Recursive $child)
    {
    }
}
