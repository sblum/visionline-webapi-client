<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\Order;

class OrderTest extends TestCase
{
    const FIELD = 'myField';

    public function testConstruct()
    {
        $order = new Order(
            self::FIELD,
            true,
            true
        );

        $this->assertSame([self::FIELD], $order->fields);
        $this->assertTrue($order->asc);
        $this->assertTrue($order->random);
    }

    public function testAsc()
    {
        $order = Order::asc(self::FIELD);

        $this->assertSame([self::FIELD], $order->fields);
        $this->assertTrue($order->asc);
        $this->assertFalse($order->random);
    }

    public function testDesc()
    {
        $order = Order::desc(self::FIELD);

        $this->assertSame([self::FIELD], $order->fields);
        $this->assertFalse($order->asc);
        $this->assertFalse($order->random);
    }

    public function testRandom()
    {
        $order = Order::random();

        $this->assertSame([null], $order->fields);
        $this->assertTrue($order->random);
    }

    public function test__toString()
    {
        $random = Order::random();
        $this->assertSame('random', $random->__toString());

        $asc = Order::asc(self::FIELD);
        $this->assertSame('myField', $asc->__toString());

        $desc = Order::desc(self::FIELD);
        $this->assertSame('desc:myField', $desc->__toString());
    }

    public function testFromString()
    {
        $this->assertEquals(Order::fromString('random'), Order::random());
        $this->assertEquals(Order::fromString('desc:myField'), Order::desc(self::FIELD));
        $this->assertEquals(Order::fromString('myField'), Order::asc(self::FIELD));

        $this->assertNull(Order::fromString(''));
    }
}
