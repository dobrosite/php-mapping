<?php

declare(strict_types=1);

namespace Tests\Fixture;

final class SyntheticConstructor
{
    private readonly string $combined;

    public static function new(string $foo, string $bar): self
    {
        return new self($foo, $bar);
    }

    public function __construct(string $foo, string $bar)
    {
        $this->combined = $foo . $bar;
    }

    public function getCombined(): string
    {
        return $this->combined;
    }
}
