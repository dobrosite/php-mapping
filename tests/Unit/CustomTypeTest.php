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
    public static function toPhpDataProvider(): iterable
    {
        return [
            'Имя функции' => [
                'givenValue' => 'foo',
                'expectedValue' => 'FOO',
                'arguments' => ['strtoupper'],
            ],
            'Анонимная функция' => [
                'givenValue' => 'FOO',
                'expectedValue' => 'foo',
                'arguments' => [fn(string $value): string => \strtolower($value)],
            ],
        ];
    }

    protected function createType(mixed ...$parameters): Type
    {
        return new CustomType(...$parameters);
    }
}
