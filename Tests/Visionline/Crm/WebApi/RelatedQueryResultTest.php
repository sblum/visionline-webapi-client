<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\QueryResult;
use Visionline\Crm\WebApi\RelatedQueryResult;

class RelatedQueryResultTest extends TestCase
{
    const RELATED_TO = 987;

    public function testConstruct()
    {
        $relatedQueryResult = new RelatedQueryResult(
            QueryResultTest::ID,
            QueryResultTest::LAST_MODIFIED,
            self::RELATED_TO
        );

        $this->assertTrue($relatedQueryResult instanceof QueryResult);
        $this->assertSame(self::RELATED_TO, $relatedQueryResult->relatedTo);

        $this->assertSame(QueryResultTest::ID, $relatedQueryResult->id);
    }

    public function testInit()
    {
        $relatedQueryResult = new RelatedQueryResult(123, 'now');

        $relatedQueryResult->init();
        $this->assertSame(\time(), $relatedQueryResult->lastModified);
    }
}
