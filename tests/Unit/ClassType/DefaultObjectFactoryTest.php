<?php

declare(strict_types=1);

namespace Tests\Unit\ClassType;

use DobroSite\Mapping\ClassType\DefaultObjectFactory;
use DobroSite\Mapping\ClassType\Properties;
use DobroSite\Mapping\ClassType\Property;
use DobroSite\Mapping\Data\DataSet;
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
            new DataSet([
                'foo' => 'FOO',
                'bar' => 'BAR',
            ])
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
            new DataSet([
                'foo' => 'FOO',
                'bar' => 'BAR',
            ])
        );

        self::assertInstanceOf(ClassWithConstructor::class, $object);
        self::assertEquals('FOO', $object->getFoo());
        self::assertEquals('BAR', $object->getBar());
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
            new DataSet([])
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
            new DataSet([])
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
            new DataSet([
                'foo' => 'FOO',
            ])
        );

        self::assertInstanceOf(ClassWithConstructor::class, $object);
        self::assertEquals('FOO', $object->getFoo());
        self::assertEquals('default', $object->getBar());
    }

    /**
     * @throws \Throwable
     */
    public function testUseDefaultValueWithConstructor(): void
    {
        $factory = new DefaultObjectFactory();
        $object = $factory->createObject(
            new \ReflectionClass(ClassWithConstructor::class),
            new Properties(
                new Property('foo'),
                new Property('bar', defaultValue: new DefaultValue('DEFAULT')),
            ),
            new DataSet([
                'foo' => 'FOO',
            ])
        );

        self::assertInstanceOf(ClassWithConstructor::class, $object);
        self::assertEquals('FOO', $object->getFoo());
        self::assertEquals('DEFAULT', $object->getBar());
    }

    /**
     * @throws \Throwable
     */
    public function testUseDefaultValueWithoutConstructor(): void
    {
        $factory = new DefaultObjectFactory();
        $object = $factory->createObject(
            new \ReflectionClass(ClassWithoutConstructor::class),
            new Properties(
                new Property('foo'),
                new Property('bar', defaultValue: new DefaultValue('DEFAULT')),
            ),
            new DataSet([
                'foo' => 'FOO',
            ])
        );

        self::assertInstanceOf(ClassWithoutConstructor::class, $object);
        self::assertEquals('FOO', $object->foo);
        self::assertEquals('DEFAULT', $object->bar);
    }
}
