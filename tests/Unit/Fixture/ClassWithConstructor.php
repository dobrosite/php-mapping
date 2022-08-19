<?php

declare(strict_types=1);

namespace Tests\Unit\Fixture;

final class ClassWithConstructor
{
    public static function new(string $foo, string $bar): self
    {
        return new self($foo, $bar);
    }

    public function __construct(
        public readonly string $foo,
        public readonly string $bar = 'default',
    ) {
    }
}
