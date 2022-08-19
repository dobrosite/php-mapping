<?php

declare(strict_types=1);

namespace Tests\Unit\ClassType;

use DobroSite\Mapping\ClassType\ClassName;
use DobroSite\Mapping\Exception\ConfigurationError;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\ClassType\ClassName
 */
final class ClassNameTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testClassNotExists(): void
    {
        $resolver = new ClassName('SomeNotExistedClass');

        $this->expectExceptionObject(
            new ConfigurationError(
                'Cannot resolve class name: class "SomeNotExistedClass" not exist.'
            )
        );

        $resolver->getTargetClass([]);
    }

    /**
     * @throws \Throwable
     */
    public function testResolveName(): void
    {
        $resolver = new ClassName(\stdClass::class);
        self::assertEquals(new \ReflectionClass(\stdClass::class), $resolver->getTargetClass([]));
    }
}
