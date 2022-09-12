<?php

declare(strict_types=1);

namespace Tests\Fixture;

function test_factory_function(string $foo, string $bar): ClassWithConstructor
{
    return new ClassWithConstructor($foo, $bar);
}
