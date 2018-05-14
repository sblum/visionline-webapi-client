<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\Connection;
use Visionline\Crm\WebApi\WebApi;

class FileTest extends TestCase
{
    public function testGetFileWithDefaultStreamContext()
    {
        $connection = new Connection('app2.visionline.at', 5000, 'testUser', 'testPassword');

        $webApi = new WebApi('https://app2.visionline.at:8443/WebApi/WebApi.asmx?WSDL', $connection, [
            'debug' => true,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Could not get file extension from https://app2.visionline.at:8443/WebApi/GetFile.ashx?host=app2.visionline.at&port=5000&username=testUser&password=testPassword&id=1');

        $webApi->getFile(1);
    }
}
