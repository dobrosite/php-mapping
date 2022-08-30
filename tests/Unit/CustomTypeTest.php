<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\CustomType;
use DobroSite\Mapping\Type;

/**
 * @covers \DobroSite\Mapping\CustomType
 */
final class CustomTypeTest extends TypeTestCase
{
    public static function toDataValueDataProvider(): iterable
    {
        return [
            'Имя функции' => [
                'givenValue' => 'FOO',
                'expectedValue' => 'foo',
                'arguments' => ['strtoupper', 'strtolower'],
            ],
            'Анонимная функция' => [
                'givenValue' => 'FOO',
                'expectedValue' => 'foo',
                'arguments' => [fn() => null, fn(string $value): string => \strtolower($value)],
            ],
        ];
    }

    public static function toPhpValueDataProvider(): iterable
    {
        return [
            'Имя функции' => [
                'givenValue' => 'foo',
                'expectedValue' => 'FOO',
                'arguments' => ['strtoupper', 'strtolower'],
            ],
            'Анонимная функция' => [
                'givenValue' => 'FOO',
                'expectedValue' => 'foo',
                'arguments' => [fn(string $value): string => \strtolower($value), fn() => null],
            ],
        ];
    }

    protected function createType(mixed ...$parameters): Type
    {
        return new CustomType(...$parameters);
    }
}
