<?php

namespace SymfonyCasts\ObjectTranslationBundle\Mapping;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class TranslatableProperty
{
    public function __construct(
        public string $type = 'string',
    ) {
    }
}
