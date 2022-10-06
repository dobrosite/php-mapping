<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\OutputMapper;
use PHPUnit\Framework\TestCase;

abstract class OutputMapperTestCase extends TestCase
{
    /**
     * @return iterable<string|int, array<mixed>>
     *
     * @throws \Throwable
     */
    abstract public static function outputDataProvider(): iterable;

    /**
     * @throws \Throwable
     */
    abstract public function testInvalidOutputType(): void;

    /**
     * @param array<mixed> $mapperConstructorArgs
     *
     * @throws \Throwable
     *
     * @dataProvider outputDataProvider
     */
    public function testOutput(
        mixed $given,
        mixed $expected,
        array $mapperConstructorArgs = []
    ): void {
        self::assertEquals(
            $expected,
            $this->createMapper(...$mapperConstructorArgs)->output($given)
        );
    }

    /**
     * @throws \Throwable
     */
    abstract protected function createMapper(mixed ...$arguments): OutputMapper;
}
