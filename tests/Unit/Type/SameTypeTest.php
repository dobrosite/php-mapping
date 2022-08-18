<?php

declare(strict_types=1);

namespace Tests\Unit\Type;

use DobroSite\Mapping\Type\SameType;
use DobroSite\Mapping\Type\Type;

/**
 * @covers \DobroSite\Mapping\Type\SameType
 */
final class SameTypeTest extends TypeTestCase
{
    /**
     * Поставщик данных для {@see testToPhpValue}.
     *
     * @return iterable<string, array>
     *
     * @throws \Throwable
     */
    public static function toPhpDataProvider(): iterable
    {
        return [
            'Строка' => ['foo', 'foo'],
            'Целое число' => [123, 123],
        ];
    }

    protected function createType(): Type
    {
        return new SameType();
    }
}
