<?php

declare(strict_types=1);

namespace Tests\Integration\ClassType;

use DobroSite\Mapping\ClassType;
use DobroSite\Mapping\NullableType;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Recursive;

/**
 * @coversNothing
 */
final class ToDataTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testToDataValue(): void
    {
        $type = new ClassType(
            new ClassType\ClassName(Recursive::class),
            new ClassType\Properties(
                new ClassType\Property(
                    propertyName: 'child',
                    dataName: 'ChildNode',
                    type: new NullableType(
                        new ClassType(
                            new ClassType\ClassName(Recursive::class),
                            new ClassType\Properties(
                                new ClassType\Property(
                                    propertyName: 'child',
                                    dataName: 'ChildNode',
                                    type: new NullableType(
                                        new ClassType(
                                            new ClassType\ClassName(Recursive::class),
                                            new ClassType\Properties(),
                                        )
                                    ),
                                )
                            ),
                        )
                    ),
                ),
            ),
        );

        $object = new Recursive();
        $object->child = new Recursive();
        $object->child->child = new Recursive();

        $data = $type->toDataValue($object);

        self::assertSame(
            [
                'ChildNode' => [
                    'ChildNode' => [
                    ]
                ],
            ],
            $data
        );
    }
}
