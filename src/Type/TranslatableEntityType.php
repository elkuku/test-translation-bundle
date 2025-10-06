<?php

namespace App\Type;

class TranslatableEntityType
{
    public function __construct(
        public string $className,

        /**
         * @var array<string, string> Property name as key, type as value
         */
        public array  $properties,
    )
    {
    }
}
