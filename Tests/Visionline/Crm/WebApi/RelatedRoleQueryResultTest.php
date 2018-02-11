<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\QueryResult;
use Visionline\Crm\WebApi\RelatedQueryResult;
use Visionline\Crm\WebApi\RelatedRoleQueryResult;

class RelatedRoleQueryResultTest extends TestCase
{
    const ROLE = 'myRole';

    public function testConstruct()
    {
        $relatedRoleQueryResult = new RelatedRoleQueryResult(
            QueryResultTest::ID,
            QueryResultTest::LAST_MODIFIED,
            RelatedQueryResultTest::RELATED_TO,
            self::ROLE
        );

        $this->assertTrue($relatedRoleQueryResult instanceof RelatedQueryResult);
        $this->assertTrue($relatedRoleQueryResult instanceof QueryResult);
        $this->assertSame(self::ROLE, $relatedRoleQueryResult->role);

        $this->assertSame(RelatedQueryResultTest::RELATED_TO, $relatedRoleQueryResult->relatedTo);
        $this->assertSame(QueryResultTest::ID, $relatedRoleQueryResult->id);
    }
}
