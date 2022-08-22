<?php

declare(strict_types=1);

namespace Tests\Unit\ClassType;

use DobroSite\Mapping\ClassType\CallableObjectFactory;
use DobroSite\Mapping\ClassType\Properties;
use DobroSite\Mapping\ClassType\Property;
use DobroSite\Mapping\Exception\ConfigurationError;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\ClassWithConstructor;
use Tests\Fixture\ClassWithoutConstructor;
use Tests\Fixture\TestFactory;

/**
 * @covers \DobroSite\Mapping\ClassType\CallableObjectFactory
 * @covers \DobroSite\Mapping\ClassType\AbstractObjectFactory
 */
final class CallableObjectFactoryTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testFactoryMethod(): void
    {
        /** @noRector \Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector */
        $factory = new CallableObjectFactory([new TestFactory(), 'create']);
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

        self::assertInstanceOf(ClassWithoutConstructor::class, $object);
        self::assertEquals('FOO', $object->foo);
        self::assertEquals('BAR', $object->bar);
    }

    /**
     * @throws \Throwable
     */
    public function testFunctionFactory(): void
    {
        $factory = new CallableObjectFactory(
            fn(string $foo, string $bar) => new ClassWithConstructor($foo, $bar)
        );
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
    public function testFunctionFactoryNotExists(): void
    {
        $this->expectExceptionObject(
            new ConfigurationError('Factory function "some_function" not found.')
        );

        $class = new \ReflectionClass(CallableObjectFactory::class);
        $factory = $class->newInstanceWithoutConstructor();
        $property = new \ReflectionProperty($factory, 'callable');
        $property->setAccessible(true);
        $property->setValue($factory, 'some_function');

        $factory->createObject(
            new \ReflectionClass(ClassWithoutConstructor::class),
            new Properties(),
            []
        );
    }

    /**
     * @throws \Throwable
     */
    public function testStaticFactoryClassNotExists(): void
    {
        $this->expectExceptionObject(
            new ConfigurationError('Factory class "SomeClass" not found.')
        );

        $class = new \ReflectionClass(CallableObjectFactory::class);
        $factory = $class->newInstanceWithoutConstructor();
        $property = new \ReflectionProperty($factory, 'callable');
        $property->setAccessible(true);
        $property->setValue($factory, ['SomeClass', 'create']);

        $factory->createObject(
            new \ReflectionClass(ClassWithoutConstructor::class),
            new Properties(),
            []
        );
    }

    /**
     * @throws \Throwable
     */
    public function testStaticFactoryMethod(): void
    {
        $factory = new CallableObjectFactory([ClassWithConstructor::class, 'new']);
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
    public function testStaticFactoryMethodNotExists(): void
    {
        $this->expectExceptionObject(
            new ConfigurationError(
                \sprintf('Factory method "%s::create" not found.', ClassWithoutConstructor::class)
            )
        );

        $class = new \ReflectionClass(CallableObjectFactory::class);
        $factory = $class->newInstanceWithoutConstructor();
        $property = new \ReflectionProperty($factory, 'callable');
        $property->setAccessible(true);
        $property->setValue($factory, [ClassWithoutConstructor::class, 'create']);

        $factory->createObject(
            new \ReflectionClass(ClassWithoutConstructor::class),
            new Properties(),
            []
        );
    }

    /**
     * @throws \Throwable
     */
    public function testStringFunctionFactory(): void
    {
        require_once \dirname(__DIR__, 2) . '/Fixture/factory_function.php';

        $factory = new CallableObjectFactory('test_factory_function');
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
}
