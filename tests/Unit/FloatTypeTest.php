<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\FloatType;
use DobroSite\Mapping\Type;

/**
 * @covers \DobroSite\Mapping\FloatType
 */
final class FloatTypeTest extends TypeTestCase
{
    public static function toDataValueDataProvider(): iterable
    {
        return [
            'Без дробной части (en)' => [
                'givenValue' => 1_234_567.00,
                'expectedValue' => '1,234,567',
                'arguments' => [new \NumberFormatter('en', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'С дробной частью (en)' => [
                'givenValue' => 1_234_567.89,
                'expectedValue' => '1,234,567.89',
                'arguments' => [new \NumberFormatter('en', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'Без дробной части (ru_RU)' => [
                'givenValue' => 1_234_567.00,
                'expectedValue' => '1 234 567',
                'arguments' => [new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'С дробной частью (ru_RU)' => [
                'givenValue' => 1_234_567.89,
                'expectedValue' => '1 234 567,89',
                'arguments' => [new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)],
            ],
        ];
    }

    public static function toPhpValueDataProvider(): iterable
    {
        return [
            'Целое число' => [
                'givenValue' => 1_234_567,
                'expectedValue' => 1_234_567,
            ],
            'Вещественное число' => [
                'givenValue' => 1_234_567.89,
                'expectedValue' => 1_234_567.89,
            ],
            'Целое число как строка (en)' => [
                'givenValue' => '1234567',
                'expectedValue' => 1_234_567,
                'arguments' => [new \NumberFormatter('en', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'Вещественное число как строка (en)' => [
                'givenValue' => '1234567.89',
                'expectedValue' => 1_234_567.89,
                'arguments' => [new \NumberFormatter('en', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'Целое число как строка (ru_RU)' => [
                'givenValue' => '1234567',
                'expectedValue' => 1_234_567,
                'arguments' => [new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'Целое число как строка с пробелами (ru_RU)' => [
                'givenValue' => '1 234 567',
                'expectedValue' => 1_234_567,
                'arguments' => [new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)],
            ],
            'Вещественное число как строка (ru_RU)' => [
                'givenValue' => '1234567,89',
                'expectedValue' => 1_234_567.89,
                'arguments' => [new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)],
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidDataValueType(): void
    {
        $type = new FloatType();

        $this->expectExceptionObject(
            new DataError(
                'Value for FloatType should be either integer, float or string, but NULL given.'
            )
        );

        $type->toPhpValue(null);
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidPhpValueType(): void
    {
        $type = new FloatType();

        $this->expectExceptionObject(
            new DataError('PHP value for FloatType should float, but string given.')
        );

        $type->toDataValue('123.45');
    }

    protected function createType(mixed ...$parameters): Type
    {
        return new FloatType(...$parameters);
    }
}
