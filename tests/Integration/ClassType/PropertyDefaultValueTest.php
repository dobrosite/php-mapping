<?php

declare(strict_types=1);

namespace Tests\Integration\ClassType;

use DobroSite\Mapping\ClassType;
use DobroSite\Mapping\DefaultValue;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Recursive;

final class PropertyDefaultValueTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testValueNotSpecified(): void
    {
        $object = $this->createTestMapping()->toPhpValue([]);

        self::assertInstanceOf(Recursive::class, $object);
        self::assertInstanceOf(Recursive::class, $object->child);
    }

    /**
     * @throws \Throwable
     */
    public function testValueSpecified(): void
    {
        $object = $this->createTestMapping()->toPhpValue(['ChildNode' => []]);

        self::assertInstanceOf(Recursive::class, $object);
        self::assertInstanceOf(Recursive::class, $object->child);
    }

    private function createTestMapping(): ClassType
    {
        return new ClassType(
            new ClassType\ClassName(Recursive::class),
            new ClassType\Properties(
                new ClassType\Property(
                    propertyName: 'child',
                    dataName: 'ChildNode',
                    type: new ClassType(
                        new ClassType\ClassName(Recursive::class),
                        new ClassType\Properties(),
                    ),
                    defaultValue: new DefaultValue([]),
                )
            )
        );
    }
}
