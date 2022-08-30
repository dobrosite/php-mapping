<?php

declare(strict_types=1);

namespace Tests\Unit\ClassType;

use DobroSite\Mapping\ClassType\Property;
use DobroSite\Mapping\Exception\ConfigurationError;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\ClassType\Property
 */
final class PropertyTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testDataNameEqualToPropertyNameByDefault(): void
    {
        $property = new Property('foo');
        self::assertSame('foo', $property->dataName);
    }

    /**
     * @throws \Throwable
     */
    public function testGetValue(): void
    {
        $object = new \stdClass();
        $object->foo = 'FOO';

        $property = new Property(propertyName: 'foo', dataName: 'Foo');
        self::assertSame('FOO', $property->getValue($object));
    }

    /**
     * @throws \Throwable
     */
    public function testSetAdHocPublicValue(): void
    {
        $object = new \stdClass();
        $object->foo = 'BAR';

        $property = new Property('foo');
        $property->setValue($object, 'FOO');
        self::assertEquals('FOO', $object->foo);
    }

    /**
     * @throws \Throwable
     */
    public function testSetNotExistedPublicValue(): void
    {
        $this->expectExceptionObject(
            new ConfigurationError('Object of class stdClass does not has property "foo".')
        );

        $property = new Property('foo');
        $property->setValue(new \stdClass(), 'FOO');
    }
}
