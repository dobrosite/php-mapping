<?php

declare(strict_types=1);

namespace Tests\Integration\ClassType;

use DobroSite\Mapping\ClassType;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Recursive;
use Tests\Fixture\RecursiveWithConstructor;

/**
 * @coversNothing
 */
final class SetObjectPropertyTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testSetObjectProperty(): void
    {
        $class = new ClassType(
            new ClassType\ClassName(RecursiveWithConstructor::class),
            new ClassType\Properties(
                new ClassType\Property(
                    propertyName: 'child',
                    dataName: 'ChildNode',
                    type: new ClassType(
                        new ClassType\ClassName(Recursive::class),
                        new ClassType\Properties(),
                    ),
                ),
            ),
        );

        $object = $class->toPhpValue(['ChildNode' => []]);

        self::assertInstanceOf(RecursiveWithConstructor::class, $object);
        self::assertInstanceOf(Recursive::class, $object->child);
    }
}
