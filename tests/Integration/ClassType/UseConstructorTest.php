<?php

declare(strict_types=1);

namespace Tests\Integration\ClassType;

use DobroSite\Mapping\ClassType;
use DobroSite\Mapping\DefaultValue;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\ClassWithConstructor;
use Tests\Fixture\SyntheticConstructor;

final class UseConstructorTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testConstructorArgumentDoesNotMatchProperty(): void
    {
        $class = new ClassType(
            new ClassType\ClassName(SyntheticConstructor::class),
            new ClassType\Properties(
                new ClassType\Property(
                    propertyName: 'foo',
                    defaultValue: new DefaultValue('WRONG!')
                ),
                new ClassType\Property(
                    propertyName: 'bar',
                    defaultValue: new DefaultValue('WRONG!')
                ),
            ),
            factory: new ClassType\CallableObjectFactory([SyntheticConstructor::class, 'new'])
        );
        $object = $class->toPhpValue(['foo' => 'FOO', 'bar' => 'BAR']);

        self::assertInstanceOf(SyntheticConstructor::class, $object);
        self::assertSame('FOOBAR', $object->getCombined());
    }

    /**
     * @throws \Throwable
     */
    public function testDoNotOverrideConstructorArguments(): void
    {
        $class = new ClassType(
            new ClassType\ClassName(ClassWithConstructor::class),
            new ClassType\Properties(
                new ClassType\Property(
                    propertyName: 'foo',
                    defaultValue: new DefaultValue('WRONG!')
                ),
                new ClassType\Property(
                    propertyName: 'bar',
                    defaultValue: new DefaultValue('WRONG!')
                ),
            ),
            factory: new ClassType\CallableObjectFactory([ClassWithConstructor::class, 'new'])
        );
        $object = $class->toPhpValue(['foo' => 'FOO', 'bar' => 'BAR']);

        self::assertInstanceOf(ClassWithConstructor::class, $object);
        self::assertSame('FOO', $object->getFoo());
        self::assertSame('BAR', $object->getBar());
    }
}
