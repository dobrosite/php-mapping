<?php

declare(strict_types=1);

namespace Tests\Integration;

use DobroSite\Mapping\ArrayDefaults;
use DobroSite\Mapping\ArrayKeysMap;
use DobroSite\Mapping\ArrayValues;
use DobroSite\Mapping\Callback;
use DobroSite\Mapping\Chained;
use DobroSite\Mapping\Constant;
use DobroSite\Mapping\Constructor;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Address;
use Tests\Fixture\Lead;

use function Tests\faker;

final class MappingTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testMapToAndFrom(): void
    {
        $phone = faker()->e164PhoneNumber();
        $city = faker()->city();

        $mapper = new Chained(
            new ArrayDefaults([
                'City' => $city,
            ]),
            new ArrayKeysMap([
                'Phone' => 'phone',
                'City' => 'address',
            ]),
            new ArrayValues([
                'address' => new Chained(
                    new Callback(
                        input: fn(string $city) => ['city' => $city],
                        output: fn(array $address) => $address['city'],
                    ),
                    new Constructor(new Constant(input: Address::class)),
                ),
            ]),
            new Constructor(new Constant(input: Lead::class)),
        );

        $object = $mapper->input([
            'Phone' => $phone,
        ]);

        self::assertInstanceOf(Lead::class, $object);
        self::assertSame($phone, $object->phone);
        self::assertSame($city, $object->address->city);

        $output = $mapper->output($object);

        self::assertEquals(
            [
                'Phone' => $phone,
                'City' => $city,
            ],
            $output
        );
    }
}
