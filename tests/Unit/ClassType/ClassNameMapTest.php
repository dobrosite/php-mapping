<?php

declare(strict_types=1);

namespace Tests\Unit\ClassType;

use DobroSite\Mapping\ClassType\ClassNameMap;
use DobroSite\Mapping\Exception\ConfigurationError;
use DobroSite\Mapping\Exception\DataError;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\ClassType\ClassNameMap
 */
final class ClassNameMapTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testClassNotExists(): void
    {
        $resolver = new ClassNameMap('Param', ['foo' => 'SomeNotExistedClass']);

        $this->expectExceptionObject(
            new ConfigurationError(
                'Cannot resolve class name: class "SomeNotExistedClass" not exist.'
            )
        );

        $resolver->getTargetClass(['Param' => 'foo']);
    }

    /**
     * @throws \Throwable
     */
    public function testParamNotSpecified(): void
    {
        $resolver = new ClassNameMap('Param', ['foo' => 'SomeNotExistedClass']);

        $this->expectExceptionObject(
            new DataError(
                'Cannot resolve class name: parameter "Param" not specified.'
            )
        );

        $resolver->getTargetClass([]);
    }

    /**
     * @throws \Throwable
     */
    public function testResolveName(): void
    {
        $resolver = new ClassNameMap(
            'Param',
            [
                'foo' => \RuntimeException::class,
                'bar' => \LogicException::class,
            ]
        );

        self::assertEquals(
            new \ReflectionClass(\RuntimeException::class),
            $resolver->getTargetClass(['Param' => 'foo'])
        );

        self::assertEquals(
            new \ReflectionClass(\LogicException::class),
            $resolver->getTargetClass(['Param' => 'bar'])
        );
    }

    /**
     * @throws \Throwable
     */
    public function testUnsupportedParamValue(): void
    {
        $resolver = new ClassNameMap(
            'Param',
            [
                'foo' => \RuntimeException::class,
                'bar' => \LogicException::class,
            ]
        );


        $this->expectExceptionObject(
            new DataError(
                'Cannot resolve class name: unknown value "baz" of "Param". Acceptable values are: foo, bar.'
            )
        );

        $resolver->getTargetClass(['Param' => 'baz']);
    }
}
