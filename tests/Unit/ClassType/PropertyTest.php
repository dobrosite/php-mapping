<?php

declare(strict_types=1);

namespace Tests\Unit\ClassType;

use DobroSite\Mapping\ClassType\Property;
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
        $object = new \stdClass();

        $property = new Property('foo');
        $property->setValue($object, 'FOO');
        self::assertEquals('FOO', $object->foo);
    }
}
