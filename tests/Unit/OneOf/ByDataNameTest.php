<?php

declare(strict_types=1);

namespace Tests\Unit\OneOf;

use DobroSite\Mapping\ClassType;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\OneOf\ByDataName;
use DobroSite\Mapping\Type;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Bar;
use Tests\Fixture\Foo;

/**
 * @covers \DobroSite\Mapping\OneOf\ByDataName
 */
final class ByDataNameTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testDataIsNotAnArray(): void
    {
        $type = new ByDataName([
            'Foo' => new ClassType(
                new ClassType\ClassName(Foo::class),
                new ClassType\Properties(new ClassType\Property('foo', 'Foo'))
            ),
            'Bar' => new ClassType(
                new ClassType\ClassName(Bar::class),
                new ClassType\Properties(new ClassType\Property('bar', 'Bar'))
            ),
        ]);

        $this->expectExceptionObject(
            new DataError('Input data should be an array, \'foo\' given.')
        );

        $type->toPhpValue('foo');
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidMapKeyType(): void
    {
        $this->expectExceptionObject(
            new ConfigurationError('Map keys should be a strings for ByDataName, 0 given.')
        );

        new ByDataName([
            'Foo' => new ClassType(
                new ClassType\ClassName(Foo::class),
                new ClassType\Properties(new ClassType\Property('foo', 'Foo'))
            ),
            new ClassType(
                new ClassType\ClassName(Bar::class),
                new ClassType\Properties(new ClassType\Property('bar', 'Bar'))
            ),
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidMapValueType(): void
    {
        $this->expectExceptionObject(
            new ConfigurationError(
                \sprintf(
                    'Map values should be an instances of %s for ByDataName, NULL given for "Bar".',
                    Type::class
                )
            )
        );

        new ByDataName([
            'Foo' => new ClassType(
                new ClassType\ClassName(Foo::class),
                new ClassType\Properties(new ClassType\Property('foo', 'Foo'))
            ),
            'Bar' => null,
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function testNoMatchingData(): void
    {
        $type = new ByDataName([
            'Foo' => new ClassType(
                new ClassType\ClassName(Foo::class),
                new ClassType\Properties(new ClassType\Property('foo', 'Foo'))
            ),
            'Bar' => new ClassType(
                new ClassType\ClassName(Bar::class),
                new ClassType\Properties(new ClassType\Property('bar', 'Bar'))
            ),
        ]);

        $this->expectExceptionObject(
            new DataError(
                'Input data should contain at least one of keys: "Foo", "Bar". Actual keys: "FOO", "BAR".'
            )
        );

        $type->toPhpValue(['FOO' => 'foo', 'BAR' => 'bar']);
    }

    /**
     * @throws \Throwable
     */
    public function testToPhpValue(): void
    {
        $type = new ByDataName([
            'Foo' => new ClassType(
                new ClassType\ClassName(Foo::class),
                new ClassType\Properties(new ClassType\Property('foo', 'Foo'))
            ),
            'Bar' => new ClassType(
                new ClassType\ClassName(Bar::class),
                new ClassType\Properties(new ClassType\Property('bar', 'Bar'))
            ),
        ]);

        $value = $type->toPhpValue(['Foo' => 'foo']);
        self::assertInstanceOf(Foo::class, $value);
        self::assertSame('foo', $value->foo);

        $value = $type->toPhpValue(['Bar' => 'bar']);
        self::assertInstanceOf(Bar::class, $value);
        self::assertSame('bar', $value->bar);

        $value = $type->toPhpValue(['Foo' => 'foo', 'Bar' => 'bar']);
        self::assertInstanceOf(Foo::class, $value);
        self::assertSame('foo', $value->foo);
    }
}
