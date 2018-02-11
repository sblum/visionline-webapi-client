<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\Connection;
use Visionline\Crm\WebApi\WebApi;

class WebApiTest extends TestCase
{
    const HOST = 'app.visionline.at';
    const PORT = 5000;
    const USERNAME = 'testUser';
    const PASSWORD = 'testPassword';
    const ENDPOINT = 'https://app2.visionline.at:8443/WebApi/WebApi.asmx?WSDL';

    public function testConnection()
    {
        $connection = new Connection(
            self::HOST,
            self::PORT,
            self::USERNAME,
            self::PASSWORD
        );

        $this->assertAttributeSame(self::HOST, 'host', $connection);
        $this->assertAttributeSame(self::PORT, 'port', $connection);
        $this->assertAttributeSame(self::USERNAME, 'username', $connection);
        $this->assertAttributeSame(self::PASSWORD, 'password', $connection);
    }

    public function testWebApi()
    {
        $connection = new Connection(
            self::HOST,
            self::PORT,
            self::USERNAME,
            self::PASSWORD
        );

        $webApi = new WebApi(
            self::ENDPOINT,
            $connection,
            [
                'debug' => true,
            ]
        );

        $this->assertAttributeSame($connection, 'connection', $webApi);
        $this->assertAttributeSame(self::ENDPOINT, 'endpoint', $webApi);
    }
}
