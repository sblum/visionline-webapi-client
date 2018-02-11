<?php


namespace Tests\Visionline\Crm\WebApi;

use Tests\AssertAttributesTrait;
use Visionline\Crm\WebApi\EnumFieldsResult;
use PHPUnit\Framework\TestCase;

class EnumFieldsResultTest extends TestCase
{
    use AssertAttributesTrait;

    public function testPublicAttributes()
    {
        $enumFieldResult = new EnumFieldsResult();

        $this->assertPublicAttributes($enumFieldResult, ['field', 'name']);
    }
}
