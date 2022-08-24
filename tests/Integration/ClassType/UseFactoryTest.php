<?php

declare(strict_types=1);

namespace Tests\Integration\ClassType;

use DobroSite\Mapping\ClassType;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\ClassWithConstructor;

/**
 * @coversNothing
 */
final class UseFactoryTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testNoIntermediateArgument(): void
    {
        $class = new ClassType(
            new ClassType\ClassName(ClassWithConstructor::class),
            new ClassType\Properties(
                new ClassType\Property(
                    propertyName: 'foo',
                ),
                new ClassType\Property(
                    propertyName: 'bar',
                ),
            ),
            factory: new ClassType\CallableObjectFactory([
                ClassWithConstructor::class,
                'withOptionals',
            ])
        );
        $object = $class->toPhpValue(['bar' => 'BAR']);

        self::assertInstanceOf(ClassWithConstructor::class, $object);
        self::assertSame('Default foo', $object->getFoo());
        self::assertSame('BAR', $object->getBar());
    }
}
