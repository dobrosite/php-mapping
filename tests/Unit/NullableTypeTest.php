<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\FloatType;
use DobroSite\Mapping\MapType;
use DobroSite\Mapping\NullableType;
use DobroSite\Mapping\Type;

/**
 * @covers \DobroSite\Mapping\NullableType
 */
final class NullableTypeTest extends TypeTestCase
{
    public static function toPhpDataProvider(): iterable
    {
        $mainType = new MapType(['foo' => 'bar']);

        return [
            'null → null' => [
                'givenValue' => null,
                'expectedValue' => null,
                'arguments' => [$mainType],
            ],
            'foo → bar' => [
                'givenValue' => 'foo',
                'expectedValue' => 'bar',
                'arguments' => [$mainType],
            ],
        ];
    }

    protected function createType(mixed ...$parameters): Type
    {
        return new NullableType(...$parameters);
    }
}
