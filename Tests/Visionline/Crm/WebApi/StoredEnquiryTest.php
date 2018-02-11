<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Tests\AssertAttributesTrait;
use Visionline\Crm\WebApi\Enquiry;
use Visionline\Crm\WebApi\StoredEnquiry;

class StoredEnquiryTest extends TestCase
{
    use AssertAttributesTrait;

    public function testExtendsEnquiry()
    {
        $storedEnquiry = new StoredEnquiry();

        $this->assertTrue($storedEnquiry instanceof Enquiry);
    }

    public function testPublicAttributes()
    {
        $storedEnquiry = new StoredEnquiry();

        $this->assertPublicAttributes(
            $storedEnquiry,
            [
                'id',
                'status',
                'kontaktId',
                'objektIds',
                'projektIds',
                'dokumentIds',
                'betreuerIds',
                'betreuerKontaktIds',
            ]
        );
    }
}
