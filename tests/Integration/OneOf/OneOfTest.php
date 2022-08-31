<?php

declare(strict_types=1);

namespace Tests\Integration\OneOf;

use DobroSite\Mapping\ClassType;
use DobroSite\Mapping\OneOf;
use DobroSite\Mapping\OneOf\ByExistedField;
use DobroSite\Mapping\Path\SubPath;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Bar;
use Tests\Fixture\Foo;

/**
 * @covers \DobroSite\Mapping\OneOf
 * @covers \DobroSite\Mapping\OneOf\ByExistedField
 * @covers \DobroSite\Mapping\Path\SubPath
 */
final class OneOfTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testToPhpValue(): void
    {
        $type = new OneOf(
            new ByExistedField(
                'Foo',
                new ClassType(
                    new ClassType\ClassName(Foo::class),
                    new ClassType\Properties(new ClassType\Property('foo', 'Foo')),
                ),
            ),
            new OneOf\ByDiscriminator(
                'Bar',
                'bar',
                new ClassType(
                    new ClassType\ClassName(Bar::class),
                    new ClassType\Properties(new ClassType\Property('bar', 'Bar'))
                ),
            ),
            new OneOf\ByDiscriminator(
                'Bar',
                'foo',
                new ClassType(
                    new ClassType\ClassName(Foo::class),
                    new ClassType\Properties(new ClassType\Property('foo', 'Foo'))
                ),
            ),
            new ByExistedField(
                'Deep',
                new SubPath(
                    'Deep.Deeper',
                    new ClassType(
                        new ClassType\ClassName(Foo::class),
                        new ClassType\Properties(new ClassType\Property('foo', 'Foo')),
                    )
                ),
            ),
        );

        $value = $type->toPhpValue(['Foo' => 'foo']);
        self::assertInstanceOf(Foo::class, $value);
        self::assertSame('foo', $value->foo);

        $value = $type->toPhpValue(['Bar' => 'bar']);
        self::assertInstanceOf(Bar::class, $value);
        self::assertSame('bar', $value->bar);

        $value = $type->toPhpValue(['Foo' => 'foo', 'Bar' => 'foo']);
        self::assertInstanceOf(Foo::class, $value);
        self::assertSame('foo', $value->foo);

        $value = $type->toPhpValue(['Deep' => ['Deeper' => ['Foo' => 'foo']]]);
        self::assertInstanceOf(Foo::class, $value);
        self::assertSame('foo', $value->foo);
    }
}
