<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\BidirectionalMapper;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Map;

/**
 * @covers \DobroSite\Mapping\Map
 */
final class MapTest extends BidirectionalTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            [
                'givenValue' => 'foo',
                'expectedValue' => 'bar',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
            [
                'givenValue' => 'bar',
                'expectedValue' => 'baz',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
            [
                'givenValue' => 'baz',
                'expectedValue' => 'baz',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            [
                'givenValue' => 'bar',
                'expectedValue' => 'foo',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
            [
                'givenValue' => 'baz',
                'expectedValue' => 'bar',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
            [
                'givenValue' => 'foo',
                'expectedValue' => 'foo',
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
            new InvalidSourceType(
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

    protected function createMapper(mixed ...$arguments): BidirectionalMapper
    {
        return new Map(...$arguments);
    }
}
