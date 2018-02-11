<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\Filter;
use Visionline\Crm\WebApi\Junction;

class JunctionTest extends TestCase
{
    public function testConstruct()
    {
        $junction = new Junction(
            Junction::TypeAnd
        );

        $this->assertTrue($junction instanceof Filter);
        $this->assertSame(Junction::TypeAnd, $junction->type);
        $this->assertSame([], $junction->filters);
    }

    public function testAdd()
    {
        $junction = new Junction(
            Junction::TypeAnd
        );

        $filter = $this->createMock(Filter::class);

        $junction->add($filter);
        $this->assertSame([$filter], $junction->filters);
    }
}
