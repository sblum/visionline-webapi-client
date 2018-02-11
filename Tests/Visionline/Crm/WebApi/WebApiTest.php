<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\Connection;
use Visionline\Crm\WebApi\Query;
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
        $connection = $this->createConnection();

        $this->assertAttributeSame(self::HOST, 'host', $connection);
        $this->assertAttributeSame(self::PORT, 'port', $connection);
        $this->assertAttributeSame(self::USERNAME, 'username', $connection);
        $this->assertAttributeSame(self::PASSWORD, 'password', $connection);
    }

    public function testWebApi()
    {
        $connection = $this->createConnection();
        $webApi = $this->createWebApi($connection);

        $this->assertAttributeSame($connection, 'connection', $webApi);
        $this->assertAttributeSame(self::ENDPOINT, 'endpoint', $webApi);
    }

    public function testCreateQuery()
    {
        $webApi = $this->createWebApi();

        $query = $webApi->createQuery('my-type');
        $this->assertInstanceOf(Query::class, $query);
        $this->assertAttributeSame($webApi, 'webapi', $query);
    }

    public function testDebugMessage()
    {
        $expectedDebugMessage = '[WebApi] [+0ms] debug-message 123';

        $webApi = $this->createWebApi();
        $this->assertSame([], $webApi->getDebugMessages());

        $webApi->debug('debug-message', 123);
        $this->assertAttributeSame([$expectedDebugMessage], 'debugMessages', $webApi);

        $this->assertSame([$expectedDebugMessage], $webApi->getDebugMessages());
        $this->assertAttributeSame([], 'debugMessages', $webApi);
    }

    private function createConnection(): Connection
    {
        return new Connection(
            self::HOST,
            self::PORT,
            self::USERNAME,
            self::PASSWORD
        );
    }

    private function createWebApi(Connection $connection = null): WebApi
    {
        if (null === $connection) {
            $connection = $this->createConnection();
        }

        return new WebApi(
            self::ENDPOINT,
            $connection,
            [
                'debug' => true,
            ]
        );
    }
}
