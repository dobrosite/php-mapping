<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\MapType;
use DobroSite\Mapping\Type;

/**
 * @covers \DobroSite\Mapping\MapType
 */
final class MapTypeTest extends TypeTestCase
{
    public static function toPhpDataProvider(): iterable
    {
        return [
            'foo → bar' => [
                'givenValue' => 'foo',
                'expectedValue' => 'bar',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
            'bar → baz' => [
                'givenValue' => 'bar',
                'expectedValue' => 'baz',
                'arguments' => [['foo' => 'bar', 'bar' => 'baz']],
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidValueType(): void
    {
        $type = new MapType([]);

        $this->expectExceptionObject(
            new DataError(
                'MapType supports string and int values only, NULL given.'
            )
        );

        $type->toPhpValue(null);
    }

    /**
     * @throws \Throwable
     */
    public function testMissedVariant(): void
    {
        $type = new MapType(['a' => true, 'b' => false]);

        $this->expectExceptionObject(
            new DataError('Value "foo" is not in the map. Available values are: a, b.')
        );

        $type->toPhpValue('foo');
    }

    protected function createType(mixed ...$parameters): Type
    {
        $map = $parameters[0] ?? null;
        \assert(\is_array($map));

        return new MapType($map);
    }
}
