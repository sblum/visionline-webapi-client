<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\QueryResult;

class QueryResultTest extends TestCase
{
    const ID = 123;
    const LAST_MODIFIED = 1335939007;

    public function testConstruct()
    {
        $queryResult = new QueryResult(self::ID, self::LAST_MODIFIED);

        $this->assertSame(self::ID, $queryResult->id);
        $this->assertSame(self::LAST_MODIFIED, $queryResult->lastModified);
    }

    public function testInit()
    {
        $queryResult = new QueryResult(self::ID, 'now');

        $queryResult->init();
        $this->assertSame(\time(), $queryResult->lastModified);
    }
}
