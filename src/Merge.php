<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

use DobroSite\Mapping\Exception\InvalidMapping;

class Merge implements OutputMapper
{
    /**
     * @var OutputMapper[]
     */
    private readonly array $mappers;

    public function __construct(OutputMapper ...$mappers)
    {
        $this->mappers = $mappers;
    }

    /**
     * @return array<mixed>
     */
    public function output(mixed $source): array
    {
        $result = [];
        foreach ($this->mappers as $mapper) {
            $array = $mapper->output($source);
            if (!\is_array($array)) {
                throw new InvalidMapping(
                    \sprintf(
                        'All mappers specified in %s::__construct should return array, but one of them returned %s.',
                        $this::class,
                        \var_export($array, true)
                    )
                );
            }
            $result[] = $array;
        }

        return \array_merge(...$result);
    }
}
