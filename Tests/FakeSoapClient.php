<?php

namespace Tests;

use Visionline\Crm\WebApi\Connection;

class FakeSoapClient extends \SoapClient
{
    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $return;

    public function __construct(array &$params = [], array &$return = [])
    {
        $this->params = &$params;
        $this->return = &$return;
    }

    public function Create(Connection $connection, string $type, array $fields): int
    {
        $this->params['Create'] = [
            'connection' => $connection,
            'type' => $type,
            'fields' => $fields,
        ];

        return \array_shift($this->return);
    }

    public function Get(Connection $connection, string $type, array $ids, array $fields, string $language, array $idFields): array
    {
        $this->params['Get'] = [
            'connection' => $connection,
            'type' => $type,
            'ids' => $ids,
            'fields' => $fields,
            'language' => $language,
            'idFields' => $idFields,
        ];

        return \array_shift($this->return);
    }

    public function Update(Connection $connection, string $type, int $id, array $fields): void
    {
        $this->params['Update'] = [
            'connection' => $connection,
            'type' => $type,
            'id' => $id,
            'fields' => $fields,
        ];
    }
}
