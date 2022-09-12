<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\EnumType;
use DobroSite\Mapping\Mapper;
use Tests\Fixture\TestEnum;
use Tests\Fixture\TestEnum2;

/**
 * @covers \DobroSite\Mapping\EnumType
 */
final class EnumTypeTest extends MapperTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            'foo' => ['foo', TestEnum::Foo, [TestEnum::class]],
            'bar' => ['bar', TestEnum::Bar, [TestEnum::class]],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            'foo' => [TestEnum::Foo, 'foo', [TestEnum::class]],
            'bar' => [TestEnum::Bar, 'bar', [TestEnum::class]],
        ];
    }

    public function testInvalidInputType(): void
    {
        $this->expectExceptionObject(
            new \InvalidArgumentException(
                \sprintf(
                    "Argument for the %s::input should be one of [string], but array (\n) given.",
                    EnumType::class
                )
            )
        );

        $type = new EnumType(TestEnum::class);
        $type->input([]);
    }

    public function testInvalidOutputType(): void
    {
        $this->expectExceptionObject(
            new \InvalidArgumentException(
                \sprintf(
                    'Argument \'foo\' for the %s::output is not a valid enum case.',
                    EnumType::class
                )
            )
        );

        $type = new EnumType(TestEnum::class);
        $type->output('foo');
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidOutputValue(): void
    {
        $this->expectExceptionObject(
            new \DomainException(
                \sprintf('Value %s::A is not a case of %s.', TestEnum2::class, TestEnum::class)
            )
        );

        $type = new EnumType(TestEnum::class);
        $type->output(TestEnum2::A);
    }

    /**
     * @throws \Throwable
     */
    public function testNotEnum(): void
    {
        $this->expectExceptionObject(
            new \InvalidArgumentException('stdClass is not an enum type.')
        );

        new EnumType(\stdClass::class);
    }

    protected function createMapper(mixed ...$arguments): Mapper
    {
        return new EnumType(...$arguments);
    }
}
