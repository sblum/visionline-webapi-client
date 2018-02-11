<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\Filter;
use Visionline\Crm\WebApi\Order;
use Visionline\Crm\WebApi\Query;
use Visionline\Crm\WebApi\WebApi;

class QueryTest extends TestCase
{
    const TYPE = 'myType';

    public function testConstruct()
    {
        $webApi = $this->createMock(WebApi::class);
        $query = new Query($webApi, self::TYPE);

        $this->assertAttributeSame($webApi, 'webapi', $query);
        $this->assertAttributeSame(self::TYPE, 'type', $query);
    }

    public function testAdd()
    {
        $query = new Query($this->createMock(WebApi::class), self::TYPE);

        $this->assertAttributeSame([], 'filters', $query);

        $filter = $this->createMock(Filter::class);
        $query->add($filter);

        $this->assertAttributeSame([$filter], 'filters', $query);
    }

    public function testOrder()
    {
        $query = new Query($this->createMock(WebApi::class), self::TYPE);
        $order = $this->createMock(Order::class);
        $query->order($order);

        $this->assertAttributeSame([$order], 'orders', $query);
    }

    public function testAddOrder()
    {
        $query = new Query($this->createMock(WebApi::class), self::TYPE);

        $this->assertAttributeSame([], 'orders', $query);

        $order = $this->createMock(Order::class);
        $query->addOrder($order);

        $this->assertAttributeSame([$order], 'orders', $query);
    }

    public function testFirst()
    {
        $query = new Query($this->createMock(WebApi::class), self::TYPE);
        $query->first(2);

        $this->assertAttributeSame(2, 'first', $query);
    }

    public function testMax()
    {
        $query = new Query($this->createMock(WebApi::class), self::TYPE);
        $query->max(3);

        $this->assertAttributeSame(3, 'max', $query);
    }

    public function testResult()
    {
        $result = ['test' => 'result'];
        $webApi = $this->createMock(WebApi::class);
        $webApi
            ->expects($this->once())
            ->method('_Query')
            ->with($this->equalTo(self::TYPE))
            ->willReturn($result);

        $query = new Query($webApi, self::TYPE);

        $this->assertSame($result, $query->result());
    }

    public function testUniqueResult()
    {
        $result = ['test', 'result'];
        $webApi = $this->createMock(WebApi::class);
        $webApi
            ->expects($this->once())
            ->method('_Query')
            ->with($this->equalTo(self::TYPE))
            ->willReturn($result);

        $query = new Query($webApi, self::TYPE);

        $this->assertSame('test', $query->uniqueResult());
    }

    public function testFields()
    {
        $result1 = (object) ['id' => 'myId'];
        $result2 = (object) ['id' => 'myDemo'];
        $queryResults = [$result1, $result2];

        $fields = ['myName' => 'name', 'myId' => 'id', 'myDemo' => 'demo'];

        $webApi = $this->createMock(WebApi::class);
        $webApi
            ->expects($this->once())
            ->method('_Query')
            ->with($this->equalTo(self::TYPE))
            ->willReturn($queryResults);
        $webApi
            ->expects($this->once())
            ->method('get')
            ->willReturn($fields);

        $query = new Query($webApi, self::TYPE);

        $this->assertSame(
            [
                'myId' => 'id',
                'myDemo' => 'demo',
                'myName' => 'name',
            ],
            $query->fields(['field1'], ['idField1'])
        );
    }
}
