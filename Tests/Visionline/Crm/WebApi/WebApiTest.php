<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Tests\FakeSoapClient;
use Visionline\Crm\WebApi\Connection;
use Visionline\Crm\WebApi\EntityType;
use Visionline\Crm\WebApi\Query;
use Visionline\Crm\WebApi\WebApi;

class WebApiTest extends TestCase
{
    const HOST = 'app2.visionline.at';
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

    public function testCreate()
    {
        $params = [];
        $return = [2];
        $client = new FakeSoapClient($params, $return);
        $webApi = $this->createWebApiWithMockClient($client);
        $data = [
            'AktivitaetsTypAktivitaet' => 'Aktivität',
            'BetreffAktivitaet' => 'Test-Titel',
        ];

        $this->assertSame(2, $webApi->create(EntityType::Aktivität, $data));
        $this->assertSame(
            [
                [
                    'field' => 'AktivitaetsTypAktivitaet',
                    'value' => 'Aktivität',
                ],
                [
                    'field' => 'BetreffAktivitaet',
                    'value' => 'Test-Titel',
                ],
            ],
            $params['Create']['fields']
        );
    }

    public function testUpdate()
    {
        $params = [];
        $client = new FakeSoapClient($params);
        $webApi = $this->createWebApiWithMockClient($client);
        $data = [
            'AktivitaetsartAktivitaet' => 'Allgemeine Aktivität',
            'BetreffAktivitaet' => 'Test-Titel',
        ];

        $this->assertNull($webApi->update(EntityType::Aktivität, 123, $data));
        $this->assertSame(
            [
                [
                    'field' => 'AktivitaetsartAktivitaet',
                    'value' => 'Allgemeine Aktivität',
                ],
                [
                    'field' => 'BetreffAktivitaet',
                    'value' => 'Test-Titel',
                ],
            ],
            $params['Update']['fields']
        );
    }

    public function testGet()
    {
        $params = [];
        $return = [[
            (object) [
                'id' => 178287,
                'fields' => [
                    (object) ['field' => 'AktivitaetsartAktivitaet', 'value' => 'Allgemeine Aktivität'],
                    (object) ['field' => 'BetreffAktivitaet', 'value' => 'Informationen an Herr Mustermann'],
                    (object) ['field' => 'ProjekteVonAktivitaet', 'value' => null],
                ],
            ],
            (object) [
                'id' => 178347,
                'fields' => [
                    (object) ['field' => 'AktivitaetsartAktivitaet', 'value' => 'Termin'],
                    (object) ['field' => 'BetreffAktivitaet', 'value' => 'Termin Herr Mustermann vereinabart'],
                    (object) ['field' => 'ProjekteVonAktivitaet', 'value' => 215],
                ],
            ],
        ]];
        $client = new FakeSoapClient($params, $return);
        $webApi = $this->createWebApiWithMockClient($client);
        $fields = ['AktivitaetsartAktivitaet', 'BetreffAktivitaet'];
        $idFields = ['ProjektId'];

        $this->assertSame(
            [
                178287 => [
                    'AktivitaetsartAktivitaet' => 'Allgemeine Aktivität',
                    'BetreffAktivitaet' => 'Informationen an Herr Mustermann',
                    'ProjekteVonAktivitaet' => null,
                ],
                178347 => [
                    'AktivitaetsartAktivitaet' => 'Termin',
                    'BetreffAktivitaet' => 'Termin Herr Mustermann vereinabart',
                    'ProjekteVonAktivitaet' => 215,
                ],
            ],
            $webApi->get(EntityType::Aktivität, [178287, 178347], $fields, $idFields)
        );
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

    private function createWebApiWithMockClient(\SoapClient $client, Connection $connection = null): WebApi
    {
        if (null === $connection) {
            $connection = $this->createConnection();
        }

        return new WebApi(
            self::ENDPOINT,
            $connection,
            [],
            $client
        );
    }
}
