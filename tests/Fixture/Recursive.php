<?php

declare(strict_types=1);

namespace Tests\Fixture;

final class Recursive
{
    public ?Recursive $child = null;
}
