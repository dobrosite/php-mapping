<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\OneOf;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\OneOf
 */
final class OneOfTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testNotVariantMatched(): void
    {
        $type = new OneOf();

        $this->expectExceptionObject(
            new DataError('Input data does not matches any of OneOf variants.')
        );

        $type->toPhpValue([]);
    }
}
