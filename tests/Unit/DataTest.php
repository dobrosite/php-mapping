<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\ClassType\Property;
use DobroSite\Mapping\Data;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\Data
 */
final class DataTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testGetByName(): void
    {
        $data = new Data(['foo' => 'FOO', 'bar' => 'BAR']);

        self::assertSame('FOO', $data->get('foo')?->value);
        self::assertSame('BAR', $data->get('bar')?->value);
    }

    /**
     * @throws \Throwable
     */
    public function testSetDefaultValue(): void
    {
        $data = new Data(['foo' => 'FOO']);
        $data->setDefaultValue('foo', 'OFF');
        $data->setDefaultValue('bar', 'BAR');

        self::assertSame('FOO', $data->get('foo')?->value);
        self::assertSame('BAR', $data->get('bar')?->value);
    }
}
