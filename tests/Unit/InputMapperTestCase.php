<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Mapper;
use PHPUnit\Framework\TestCase;

abstract class InputMapperTestCase extends TestCase
{
    /**
     * @return iterable<string|int, array<mixed>>
     *
     * @throws \Throwable
     */
    abstract public static function inputDataProvider(): iterable;

    /**
     * @param array<mixed> $mapperConstructorArgs
     *
     * @throws \Throwable
     *
     * @dataProvider inputDataProvider
     */
    public function testInput(
        mixed $given,
        mixed $expected,
        array $mapperConstructorArgs = []
    ): void {
        self::assertEquals(
            $expected,
            $this->createMapper(...$mapperConstructorArgs)->input($given)
        );
    }

    /**
     * @throws \Throwable
     */
    abstract public function testInvalidInputType(): void;

    /**
     * @throws \Throwable
     */
    abstract protected function createMapper(mixed ...$arguments): Mapper;
}
