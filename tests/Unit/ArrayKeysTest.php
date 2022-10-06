<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\ArrayKeys;
use DobroSite\Mapping\AsIs;
use DobroSite\Mapping\Callback;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Mapper;

/**
 * @covers \DobroSite\Mapping\ArrayKeys
 */
final class ArrayKeysTest extends BidirectionalTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            [
                'given' => ['FOO' => 'foo value', 'BAR' => 'bar value'],
                'expected' => ['foo' => 'foo value', 'bar' => 'bar value'],
                'arguments' => [
                    /** @noRector */
                    new Callback(input: \strtolower(...), output: \strtoupper(...)),
                ],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            [
                'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
                'expected' => ['FOO' => 'foo value', 'BAR' => 'bar value'],
                'arguments' => [
                    /** @noRector */
                    new Callback(input: \strtolower(...), output: \strtoupper(...)),
                ],
            ],
        ];
    }

    public function testInvalidInputType(): void
    {
        $mapper = new ArrayKeys(mapper: new AsIs());

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::input should be one of [array], but 'foo' given.",
                    ArrayKeys::class
                )
            )
        );

        $mapper->input('foo');
    }

    public function testInvalidOutputType(): void
    {
        $mapper = new ArrayKeys(mapper: new AsIs());

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::output should be one of [array], but 'foo' given.",
                    ArrayKeys::class
                )
            )
        );

        $mapper->output('foo');
    }

    protected function createMapper(mixed ...$arguments): Mapper
    {
        return new ArrayKeys(...$arguments);
    }
}
