<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Map;
use DobroSite\Mapping\Mapper;

/**
 * @covers \DobroSite\Mapping\Map
 */
final class MapTest extends MapperTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            'foo → bar' => [
                'givenValue' => 'foo',
                'expectedValue' => 'bar',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
            'bar → baz' => [
                'givenValue' => 'bar',
                'expectedValue' => 'baz',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            'foo ← bar' => [
                'givenValue' => 'bar',
                'expectedValue' => 'foo',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
            'bar ← baz' => [
                'givenValue' => 'baz',
                'expectedValue' => 'bar',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidInputType(): void
    {
        $type = new Map([]);

        $this->expectExceptionObject(
            new \InvalidArgumentException(
                \sprintf(
                    'Argument for the %s::input should be one of [integer, string], but NULL given.',
                    Map::class
                )
            )
        );

        $type->input(null);
    }

    public function testInvalidOutputType(): void
    {
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws \Throwable
     */
    public function testMissedInputVariant(): void
    {
        $type = new Map(['a' => true, 'b' => false]);

        $this->expectExceptionObject(
            new \DomainException('Key "foo" is not in the map. Available values are: a, b.')
        );

        $type->input('foo');
    }


    /**
     * @throws \Throwable
     */
    public function testMissedOutputVariant(): void
    {
        $type = new Map(['a' => true, 'b' => false]);

        $this->expectExceptionObject(
            new \DomainException('Value 1 is not in the map. Available values are: true, false.')
        );

        $type->output(1);
    }

    protected function createMapper(mixed ...$arguments): Mapper
    {
        return new Map(...$arguments);
    }
}
