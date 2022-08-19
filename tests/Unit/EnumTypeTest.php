<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\EnumType;
use DobroSite\Mapping\Type;
use Tests\Unit\Fixture\TestEnum;

/**
 * @covers \DobroSite\Mapping\EnumType
 */
final class EnumTypeTest extends TypeTestCase
{
    public static function toPhpDataProvider(): iterable
    {
        return [
            'foo' => ['foo', TestEnum::Foo, [TestEnum::class]],
            'bar' => ['bar', TestEnum::Bar, [TestEnum::class]],
        ];
    }

    protected function createType(mixed ...$parameters): Type
    {
        $type = $parameters[0] ?? null;
        \assert(\is_string($type));

        return new EnumType($type);
    }
}
