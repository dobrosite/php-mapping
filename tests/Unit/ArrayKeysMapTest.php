<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\ArrayKeysMap;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Mapper;

/**
 * @covers \DobroSite\Mapping\ArrayKeysMap
 */
final class ArrayKeysMapTest extends BidirectionalTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            [
                'given' => ['foo' => 'value'],
                'expected' => ['bar' => 'value'],
                'arguments' => [
                    ['foo' => 'bar'],
                ],
            ],
            [
                'given' => ['foo' => 'foo value', 'bar' => 'bar value', 'baz' => 'baz value'],
                'expected' => ['bar' => 'foo value', 'foo' => 'bar value', 'baz' => 'baz value'],
                'arguments' => [
                    ['foo' => 'bar', 'bar' => 'foo'],
                ],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            [
                'given' => ['bar' => 'value'],
                'expected' => ['foo' => 'value'],
                'arguments' => [
                    ['foo' => 'bar'],
                ],
            ],
            [
                'given' => ['bar' => 'foo value', 'foo' => 'bar value', 'baz' => 'baz value'],
                'expected' => ['foo' => 'foo value', 'bar' => 'bar value', 'baz' => 'baz value'],
                'arguments' => [
                    ['foo' => 'bar', 'bar' => 'foo'],
                ],
            ],
        ];
    }

    public function testInvalidInputType(): void
    {
        $mapper = new ArrayKeysMap([]);

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::input should be one of [array], but 'foo' given.",
                    ArrayKeysMap::class
                )
            )
        );

        $mapper->input('foo');
    }

    public function testInvalidOutputType(): void
    {
        $mapper = new ArrayKeysMap([]);

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::output should be one of [array], but 'foo' given.",
                    ArrayKeysMap::class
                )
            )
        );

        $mapper->output('foo');
    }

    protected function createMapper(mixed ...$arguments): Mapper
    {
        return new ArrayKeysMap(...$arguments);
    }
}
