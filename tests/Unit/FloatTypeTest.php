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
    public static function toPhpDataProvider(): iterable
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
    public function testInvalidValueType(): void
    {
        $type = new FloatType();

        $this->expectExceptionObject(
            new DataError(
                'Value for FloatType should be either integer, float or string, but NULL given.'
            )
        );

        $type->toPhpValue(null);
    }

    protected function createType(mixed ...$parameters): Type
    {
        $formatter = $parameters[0] ?? null;
        \assert($formatter instanceof \NumberFormatter || $formatter === null);

        return new FloatType($formatter);
    }
}
