<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\BooleanType;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\Type;

/**
 * @covers \DobroSite\Mapping\BooleanType
 */
final class BooleanTypeTest extends TypeTestCase
{
    public static function toPhpValueDataProvider(): iterable
    {
        return [
            'true' => [
                'givenValue' => 'true',
                'expectedValue' => true,
                'arguments' => [],
            ],
            'false' => [
                'givenValue' => 'false',
                'expectedValue' => false,
                'arguments' => [],
            ],
            'да → true' => [
                'givenValue' => 'да',
                'expectedValue' => true,
                'arguments' => ['да', 'нет'],
            ],
            'нет → false' => [
                'givenValue' => 'нет',
                'expectedValue' => false,
                'arguments' => ['да', 'нет'],
            ],
            '1 → true' => [
                'givenValue' => 1,
                'expectedValue' => true,
                'arguments' => ['1', '0'],
            ],
            '0 → false' => [
                'givenValue' => 0,
                'expectedValue' => false,
                'arguments' => ['1', '0'],
            ],
            'ДА → true (без учёта регистра)' => [
                'givenValue' => 'ДА',
                'expectedValue' => true,
                'arguments' => ['да', 'нет'],
            ],
            'НЕТ → false (без учёта регистра)' => [
                'givenValue' => 'НЕТ',
                'expectedValue' => false,
                'arguments' => ['да', 'нет'],
            ],
        ];
    }

    public static function toDataValueDataProvider(): iterable
    {
        return [
            'true' => [
                'givenValue' => true,
                'expectedValue' => 'true',
                'arguments' => [],
            ],
            'false' => [
                'givenValue' => false,
                'expectedValue' => 'false',
                'arguments' => [],
            ],
            'да → true' => [
                'givenValue' => true,
                'expectedValue' => 'да',
                'arguments' => ['да', 'нет'],
            ],
            'нет → false' => [
                'givenValue' => false,
                'expectedValue' => 'нет',
                'arguments' => ['да', 'нет'],
            ],
            '1 → true' => [
                'givenValue' => true,
                'expectedValue' => 1,
                'arguments' => ['1', '0'],
            ],
            '0 → false' => [
                'givenValue' => false,
                'expectedValue' => 0,
                'arguments' => ['1', '0'],
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidValueType(): void
    {
        $type = new BooleanType();

        $this->expectExceptionObject(
            new DataError(
                'Value for the BooleanType should be a scalar, but array given.'
            )
        );

        $type->toPhpValue([]);
    }

    /**
     * @throws \Throwable
     */
    public function testUnknownValue(): void
    {
        $type = new BooleanType();

        $this->expectExceptionObject(
            new DataError(
                'Value "Foo" is not allowed for the BooleanType. Allowed values are "true" and "false".'
            )
        );

        $type->toPhpValue('Foo');
    }

    protected function createType(mixed ...$parameters): Type
    {
        return new BooleanType(...$parameters);
    }
}
