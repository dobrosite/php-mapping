<?php

declare(strict_types=1);

namespace Tests\Unit\Fixture;

final class TestFactory
{
    public function create(): ClassWithoutConstructor
    {
        return new ClassWithoutConstructor();
    }
}
