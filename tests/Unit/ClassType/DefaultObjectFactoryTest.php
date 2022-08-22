<?php

declare(strict_types=1);

namespace Tests\Unit\ClassType;

use DobroSite\Mapping\ClassType\DefaultObjectFactory;
use DobroSite\Mapping\ClassType\Properties;
use DobroSite\Mapping\ClassType\Property;
use DobroSite\Mapping\DefaultValue;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\ClassWithConstructor;
use Tests\Fixture\ClassWithoutConstructor;
use Tests\Fixture\ClassWithPrivateConstructor;

/**
 * @covers \DobroSite\Mapping\ClassType\DefaultObjectFactory
 * @covers \DobroSite\Mapping\ClassType\AbstractObjectFactory
 */
final class DefaultObjectFactoryTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testClassHasNoConstructor(): void
    {
        $factory = new DefaultObjectFactory();
        $object = $factory->createObject(
            new \ReflectionClass(ClassWithoutConstructor::class),
            new Properties(
                new Property('foo'),
                new Property('bar'),
            ),
            [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]
        );

        self::assertInstanceOf(ClassWithoutConstructor::class, $object);
        self::assertEquals('FOO', $object->foo);
        self::assertEquals('BAR', $object->bar);
    }

    /**
     * @throws \Throwable
     */
    public function testClassWithConstructor(): void
    {
        $factory = new DefaultObjectFactory();
        $object = $factory->createObject(
            new \ReflectionClass(ClassWithConstructor::class),
            new Properties(
                new Property('foo'),
                new Property('bar'),
            ),
            [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]
        );

        self::assertInstanceOf(ClassWithConstructor::class, $object);
        self::assertEquals('FOO', $object->foo);
        self::assertEquals('BAR', $object->bar);
    }

    /**
     * @throws \Throwable
     */
    public function testClassWithPrivateConstructor(): void
    {
        $this->expectExceptionObject(
            new ConfigurationError(
                ClassWithPrivateConstructor::class . '::__construct is not public. Try another factory.'
            )
        );

        $factory = new DefaultObjectFactory();
        $factory->createObject(
            new \ReflectionClass(ClassWithPrivateConstructor::class),
            new Properties(),
            []
        );
    }

    /**
     * @throws \Throwable
     */
    public function testNoValueSpecified(): void
    {
        $this->expectExceptionObject(
            new DataError('No value is specified for the "foo" parameter.')
        );

        $factory = new DefaultObjectFactory();
        $factory->createObject(
            new \ReflectionClass(ClassWithConstructor::class),
            new Properties(new Property('foo')),
            []
        );
    }

    /**
     * @throws \Throwable
     */
    public function testUseDefaultConstructorArgumentValues(): void
    {
        $factory = new DefaultObjectFactory();
        $object = $factory->createObject(
            new \ReflectionClass(ClassWithConstructor::class),
            new Properties(
                new Property('foo'),
            ),
            [
                'foo' => 'FOO',
            ]
        );

        self::assertInstanceOf(ClassWithConstructor::class, $object);
        self::assertEquals('FOO', $object->foo);
        self::assertEquals('default', $object->bar);
    }

    /**
     * @throws \Throwable
     */
    public function testUseDefaultValue(): void
    {
        $factory = new DefaultObjectFactory();
        $object = $factory->createObject(
            new \ReflectionClass(ClassWithConstructor::class),
            new Properties(
                new Property('foo'),
                new Property('bar', defaultValue: new DefaultValue('DEFAULT')),
            ),
            [
                'foo' => 'FOO',
            ]
        );

        self::assertInstanceOf(ClassWithConstructor::class, $object);
        self::assertEquals('FOO', $object->foo);
        self::assertEquals('DEFAULT', $object->bar);
    }
}
