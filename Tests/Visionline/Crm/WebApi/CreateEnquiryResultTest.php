<?php


namespace Tests\Visionline\Crm\WebApi;

use Tests\AssertAttributesTrait;
use Visionline\Crm\WebApi\CreateEnquiryResult;
use PHPUnit\Framework\TestCase;

class CreateEnquiryResultTest extends TestCase
{
    use AssertAttributesTrait;

    public function testPublicAttributes()
    {
        $createEnquiryResult = new CreateEnquiryResult();

        $this->assertPublicAttributes($createEnquiryResult, ['enquiry', 'warnings']);
    }
}
