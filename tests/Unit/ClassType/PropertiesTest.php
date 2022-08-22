<?php

declare(strict_types=1);

namespace Tests\Unit\ClassType;

use DobroSite\Mapping\ClassType\Properties;
use DobroSite\Mapping\ClassType\Property;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\ClassType\Properties
 */
final class PropertiesTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testFindByDataName(): void
    {
        $properties = new Properties(
            new Property(propertyName: 'foo', dataName: 'FOO'),
            new Property(propertyName: 'bar', dataName: 'Bar'),
        );

        self::assertSame('foo', $properties->findByDataName('FOO')->propertyName);
        self::assertSame('bar', $properties->findByDataName('Bar')->propertyName);
        self::assertSame('baz', $properties->findByDataName('baz')->propertyName);
    }

    /**
     * @throws \Throwable
     */
    public function testFindByPropertyName(): void
    {
        $properties = new Properties(
            new Property(propertyName: 'foo', dataName: 'FOO'),
            new Property(propertyName: 'bar', dataName: 'Bar'),
        );

        self::assertSame('FOO', $properties->findByPropertyName('foo')->dataName);
        self::assertSame('Bar', $properties->findByPropertyName('bar')->dataName);
        self::assertSame('baz', $properties->findByPropertyName('baz')->dataName);
    }

    /**
     * @throws \Throwable
     */
    public function testIterator(): void
    {
        $properties = new Properties(
            new Property(propertyName: 'foo'),
            new Property(propertyName: 'bar'),
            new Property(propertyName: 'baz'),
        );

        self::assertInstanceOf(\Iterator::class, $properties);

        $properties->rewind();
        self::assertTrue($properties->valid());
        self::assertSame(0, $properties->key());
        $property = $properties->current();
        self::assertInstanceOf(Property::class, $property);
        self::assertSame('foo', $property->propertyName);

        $properties->next();
        self::assertTrue($properties->valid());
        self::assertSame(1, $properties->key());
        $property = $properties->current();
        self::assertInstanceOf(Property::class, $property);
        self::assertSame('bar', $property->propertyName);

        $properties->next();
        self::assertTrue($properties->valid());
        self::assertSame(2, $properties->key());
        $property = $properties->current();
        self::assertInstanceOf(Property::class, $property);
        self::assertSame('baz', $property->propertyName);

        $properties->next();
        self::assertFalse($properties->valid());
        self::assertNull($properties->key());

        $properties->rewind();
        $property = $properties->current();
        self::assertSame(0, $properties->key());
        self::assertInstanceOf(Property::class, $property);
        self::assertSame('foo', $property->propertyName);
    }
}
