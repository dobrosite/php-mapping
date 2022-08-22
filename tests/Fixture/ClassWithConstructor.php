<?php

declare(strict_types=1);

namespace Tests\Fixture;

final class ClassWithConstructor
{
    public static function new(string $foo, string $bar): self
    {
        return new self($foo, $bar);
    }

    public function __construct(
        private readonly string $foo,
        private readonly string $bar = 'default',
    ) {
    }

    public function getBar(): string
    {
        return $this->bar;
    }

    public function getFoo(): string
    {
        return $this->foo;
    }
}
