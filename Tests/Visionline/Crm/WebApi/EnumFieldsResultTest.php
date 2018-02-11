<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Tests\AssertAttributesTrait;
use Visionline\Crm\WebApi\EnumFieldsResult;

class EnumFieldsResultTest extends TestCase
{
    use AssertAttributesTrait;

    public function testPublicAttributes()
    {
        $enumFieldResult = new EnumFieldsResult();

        $this->assertPublicAttributes($enumFieldResult, ['field', 'name']);
    }
}
