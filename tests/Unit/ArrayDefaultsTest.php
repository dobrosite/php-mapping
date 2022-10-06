<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\ArrayDefaults;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\InputMapper;

/**
 * @covers \DobroSite\Mapping\ArrayDefaults
 */
final class ArrayDefaultsTest extends InputMapperTestCase
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

    protected function createMapper(mixed ...$arguments): InputMapper
    {
        return new ArrayDefaults(...$arguments);
    }
}
