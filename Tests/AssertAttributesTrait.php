<?php

namespace Tests;

trait AssertAttributesTrait
{
    public function assertPublicAttributes($object, array $attributes): void
    {
        if (!\is_object($object)) {
            throw new \InvalidArgumentException('first parameter $object must be an object.');
        }

        foreach ($attributes as $attribute) {
            $this->assertAttributeSame($object->$attribute, $attribute, $object);
        }
    }
}
