<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\SameType;
use DobroSite\Mapping\Type;

/**
 * @covers \DobroSite\Mapping\SameType
 */
final class SameTypeTest extends TypeTestCase
{
    public static function toPhpDataProvider(): iterable
    {
        return [
            'Строка' => ['foo', 'foo'],
            'Целое число' => [123, 123],
        ];
    }

    protected function createType(mixed ...$parameters): Type
    {
        return new SameType(...$parameters);
    }
}
