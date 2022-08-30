<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\EnumType;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\Type;
use Tests\Fixture\TestEnum;
use Tests\Fixture\TestEnum2;

/**
 * @covers \DobroSite\Mapping\EnumType
 */
final class EnumTypeTest extends TypeTestCase
{
    public static function toDataValueDataProvider(): iterable
    {
        return [
            'foo' => [TestEnum::Foo, 'foo', [TestEnum::class]],
            'bar' => [TestEnum::Bar, 'bar', [TestEnum::class]],
        ];
    }

    public static function toPhpValueDataProvider(): iterable
    {
        return [
            'foo' => ['foo', TestEnum::Foo, [TestEnum::class]],
            'bar' => ['bar', TestEnum::Bar, [TestEnum::class]],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testNotEnum(): void
    {
        $this->expectExceptionObject(new ConfigurationError('stdClass is not an enum type.'));

        new EnumType(\stdClass::class);
    }

    /**
     * @throws \Throwable
     */
    public function testPhpValueIsNotAnEnumCase(): void
    {
        $this->expectExceptionObject(new DataError('Value \'foo\' is not a valid enum case.'));

        $type = new EnumType(TestEnum::class);
        $type->toDataValue('foo');
    }

    /**
     * @throws \Throwable
     */
    public function testPhpValueIsNotFromTypeEnum(): void
    {
        $this->expectExceptionObject(
            new DataError(
                \sprintf('Value %s::A is not a case of %s.', TestEnum2::class, TestEnum::class)
            )
        );

        $type = new EnumType(TestEnum::class);
        $type->toDataValue(TestEnum2::A);
    }

    /**
     * @throws \Throwable
     */
    protected function createType(mixed ...$parameters): Type
    {
        return new EnumType(...$parameters);
    }
}
