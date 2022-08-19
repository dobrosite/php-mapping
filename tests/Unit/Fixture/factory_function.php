<?php

declare(strict_types=1);

use Tests\Unit\Fixture\ClassWithConstructor;

function test_factory_function(string $foo, string $bar): ClassWithConstructor
{
    return new ClassWithConstructor($foo, $bar);
}
