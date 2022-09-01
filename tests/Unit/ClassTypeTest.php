<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\ClassType;
use DobroSite\Mapping\ClassType\Properties;
use DobroSite\Mapping\EnumType;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\Type;
use Tests\Fixture\ClassWithConstructor;
use Tests\Fixture\Foo;
use Tests\Fixture\TestEnum;

/**
 * @covers \DobroSite\Mapping\ClassType
 */
final class ClassTypeTest extends TypeTestCase
{
    public static function toDataValueDataProvider(): iterable
    {
        $object = new \stdClass();
        $object->foo = 'Foo';
        $object->bar = 'Bar';

        yield \stdClass::class => [
            'givenValue' => $object,
            'expectedValue' => ['foo' => 'Foo', 'bar' => 'Bar'],
            'parameters' => [
                new ClassType\ClassName(ClassWithConstructor::class),
                new ClassType\Properties(
                    new ClassType\Property('foo'),
                    new ClassType\Property('bar'),
                ),
            ],
        ];
    }

    public static function toPhpValueDataProvider(): iterable
    {
        yield \stdClass::class => [
            'givenValue' => [
                'foo' => 'foo',
                'bar' => 'bar',
            ],
            'expectedValue' => new ClassWithConstructor('foo', 'bar'),
            'parameters' => [
                new ClassType\ClassName(ClassWithConstructor::class),
                new ClassType\Properties(),
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testNotAnObject(): void
    {
        $type = new ClassType(
            new ClassType\ClassName(ClassWithConstructor::class),
            new ClassType\Properties(),
        );

        $this->expectExceptionObject(
            new DataError('PHP value for ClassType should be an object, string given.')
        );

        $type->toDataValue('foo');
    }

    /**
     * @throws \Throwable
     */
    public function testToDataValuePropertyException(): void
    {
        $type = new ClassType(
            new ClassType\ClassName(Foo::class),
            new Properties(
                new ClassType\Property(
                    propertyName: 'foo',
                    dataName: 'Foo',
                    type: new EnumType(TestEnum::class),
                ),
            ),
        );

        $this->expectExceptionObject(
            new ConfigurationError(
                'Cannot transform value NULL of property "foo" to data value for "Foo" field.'
            )
        );

        $object = new Foo();
        $object->foo = null;

        $type->toDataValue($object);
    }

    /**
     * @throws \Throwable
     */
    public function testTryToMapNotAnArrayValue(): void
    {
        $type = new ClassType(
            new ClassType\ClassName(\stdClass::class),
            new Properties()
        );

        $this->expectExceptionObject(
            new \InvalidArgumentException(
                'Only array values can be mapped on objects, string given.'
            )
        );

        $type->toPhpValue('foo');
    }

    protected function createType(mixed ...$parameters): Type
    {
        return new ClassType(...$parameters);
    }
}
