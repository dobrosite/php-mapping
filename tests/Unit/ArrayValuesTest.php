<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\ArrayValues;
use DobroSite\Mapping\Callback;
use DobroSite\Mapping\Mapper;

/**
 * @covers \DobroSite\Mapping\ArrayValues
 */
final class ArrayValuesTest extends MapperTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            [
                'given' => ['foo' => 'foo', 'bar' => 'bar'],
                'expected' => ['foo' => 'FOO', 'bar' => 'bar'],
                'arguments' => [
                    /** @noRector */
                    ['foo' => new Callback(input: \strtoupper(...), output: \strtolower(...))],
                ],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            [
                'given' => ['foo' => 'FOO', 'bar' => 'bar'],
                'expected' => ['foo' => 'foo', 'bar' => 'bar'],
                'arguments' => [
                    /** @noRector */
                    ['foo' => new Callback(input: \strtoupper(...), output: \strtolower(...))],
                ],
            ],
        ];
    }

    public function testInvalidInputType(): void
    {
        $mapper = new ArrayValues([]);

        $this->expectExceptionObject(
            new \InvalidArgumentException(
                \sprintf(
                    "Argument for the %s::input should be one of [array], but 'foo' given.",
                    ArrayValues::class
                )
            )
        );

        $mapper->input('foo');
    }

    public function testInvalidOutputType(): void
    {
        $mapper = new ArrayValues([]);

        $this->expectExceptionObject(
            new \InvalidArgumentException(
                \sprintf(
                    "Argument for the %s::output should be one of [array], but 'foo' given.",
                    ArrayValues::class
                )
            )
        );

        $mapper->output('foo');
    }

    protected function createMapper(mixed ...$arguments): Mapper
    {
        return new ArrayValues(...$arguments);
    }
}
