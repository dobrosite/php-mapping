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
}
