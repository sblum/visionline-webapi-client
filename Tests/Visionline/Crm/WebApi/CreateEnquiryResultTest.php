<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Tests\AssertAttributesTrait;
use Visionline\Crm\WebApi\CreateEnquiryResult;

class CreateEnquiryResultTest extends TestCase
{
    use AssertAttributesTrait;

    public function testPublicAttributes()
    {
        $createEnquiryResult = new CreateEnquiryResult();

        $this->assertPublicAttributes($createEnquiryResult, ['enquiry', 'warnings']);
    }
}
