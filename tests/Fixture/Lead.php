<?php

declare(strict_types=1);

namespace Tests\Fixture;

final class Lead
{
    public Address $address;

    public function __construct(
        public readonly string $phone
    ) {
        $this->address = new Address();
    }
}
