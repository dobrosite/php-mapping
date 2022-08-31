<?php

declare(strict_types=1);

namespace Tests\Unit\Path;

use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\Path\SubPath;
use DobroSite\Mapping\SameType;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\Path\SubPath
 */
final class SubPathTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testNotAnArrayData(): void
    {
        $this->expectExceptionObject(
            new DataError('SubPath::toPhpValue expects an array, \'foo\' given.')
        );

        $type = new SubPath('path', new SameType());
        $type->toPhpValue('foo');
    }
}
