<?php

declare(strict_types=1);

namespace Tests\Unit\Type;

use DobroSite\Mapping\Type\EnumType;
use DobroSite\Mapping\Type\Type;
use Tests\Unit\Type\Fixture\TestEnum;

/**
 * @covers \DobroSite\Mapping\Type\EnumType
 */
final class EnumTypeTest extends TypeTestCase
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
            'foo' => ['foo', TestEnum::Foo],
            'bar' => ['bar', TestEnum::Bar],
        ];
    }

    protected function createType(): Type
    {
        return new EnumType(TestEnum::class);
    }
}
