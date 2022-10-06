<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\FloatType;
use DobroSite\Mapping\Mapper;

/**
 * @covers \DobroSite\Mapping\FloatType
 */
final class FloatTypeTest extends BidirectionalTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            'Целое число' => [
                'given' => 1_234_567,
                'expected' => 1_234_567,
            ],
            'Вещественное число' => [
                'given' => 1_234_567.89,
                'expected' => 1_234_567.89,
            ],
            'Целое число как строка (en)' => [
                'given' => '1234567',
                'expected' => 1_234_567,
                'arguments' => [new \NumberFormatter('en', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'Вещественное число как строка (en)' => [
                'given' => '1234567.89',
                'expected' => 1_234_567.89,
                'arguments' => [new \NumberFormatter('en', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'Целое число как строка (ru_RU)' => [
                'given' => '1234567',
                'expected' => 1_234_567,
                'arguments' => [new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'Целое число как строка с пробелами (ru_RU)' => [
                'given' => '1 234 567',
                'expected' => 1_234_567,
                'arguments' => [new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'Вещественное число как строка (ru_RU)' => [
                'given' => '1234567,89',
                'expected' => 1_234_567.89,
                'arguments' => [new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            'Без дробной части (en)' => [
                'given' => 1_234_567.00,
                'expected' => '1,234,567',
                'arguments' => [new \NumberFormatter('en', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'С дробной частью (en)' => [
                'given' => 1_234_567.89,
                'expected' => '1,234,567.89',
                'arguments' => [new \NumberFormatter('en', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'Без дробной части (ru_RU)' => [
                'given' => 1_234_567.00,
                'expected' => '1 234 567',
                'arguments' => [new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'С дробной частью (ru_RU)' => [
                'given' => 1_234_567.89,
                'expected' => '1 234 567,89',
                'arguments' => [new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)],
            ],
        ];
    }

    public function testInvalidInputType(): void
    {
        $type = new FloatType();

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    'Argument for the %s::input should be one of [float, integer, string], but NULL given.',
                    FloatType::class
                )
            )
        );

        $type->input(null);
    }

    public function testInvalidOutputType(): void
    {
        $type = new FloatType();

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::output should be one of [float, integer], but '123' given.",
                    FloatType::class
                )
            )
        );

        $type->output('123');
    }

    protected function createMapper(mixed ...$arguments): Mapper
    {
        return new FloatType(...$arguments);
    }
}
