<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\ArrayDefaults;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Mapper;

/**
 * @covers \DobroSite\Mapping\ArrayDefaults
 */
final class ArrayDefaultsTest extends MapperTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            [
                'given' => ['foo' => 'foo value'],
                'expected' => ['foo' => 'foo value', 'bar' => 'bar value'],
                'arguments' => [
                    ['bar' => 'bar value'],
                ],
            ],
            [
                'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
                'expected' => ['foo' => 'foo value', 'bar' => 'bar value'],
                'arguments' => [
                    ['bar' => 'default value'],
                ],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            [
                'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
                'expected' => ['foo' => 'foo value', 'bar' => 'bar value'],
                'arguments' => [
                    ['bar' => 'bar value'],
                ],
            ],
        ];
    }

    public function testInvalidInputType(): void
    {
        $mapper = new ArrayDefaults([]);

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::input should be one of [array], but 'foo' given.",
                    ArrayDefaults::class
                )
            )
        );

        $mapper->input('foo');
    }

    public function testInvalidOutputType(): void
    {
        $this->expectNotToPerformAssertions();
    }

    protected function createMapper(mixed ...$arguments): Mapper
    {
        return new ArrayDefaults(...$arguments);
    }
}
