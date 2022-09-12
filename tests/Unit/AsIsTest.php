<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\AsIs;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\AsIs
 */
final class AsIsTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testInput(): void
    {
        $mapper = new AsIs();

        self::assertSame('foo', $mapper->input('foo'));
    }

    /**
     * @throws \Throwable
     */
    public function testOutput(): void
    {
        $mapper = new AsIs();

        self::assertSame('foo', $mapper->output('foo'));
    }
}
