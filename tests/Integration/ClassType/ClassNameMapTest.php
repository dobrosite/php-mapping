<?php

declare(strict_types=1);

namespace Tests\Integration\ClassType;

use DobroSite\Mapping\ClassType;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
final class ClassNameMapTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testResolveClassName(): void
    {
        $object = $this->createTestMapping()->toPhpValue(['Type' => 'runtime', 'Message' => 'Foo']);
        self::assertInstanceOf(\RuntimeException::class, $object);

        $object = $this->createTestMapping()->toPhpValue(['Type' => 'logic', 'Message' => 'Foo']);
        self::assertInstanceOf(\LogicException::class, $object);
    }

    private function createTestMapping(): ClassType
    {
        return new ClassType(
            new ClassType\ClassNameMap(
                'Type',
                [
                    'runtime' => \RuntimeException::class,
                    'logic' => \LogicException::class,
                ]
            ),
            new ClassType\Properties(
                new ClassType\Property(
                    propertyName: 'message',
                    dataName: 'Message',
                )
            )
        );
    }
}
