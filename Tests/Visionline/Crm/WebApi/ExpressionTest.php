<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Visionline\Crm\WebApi\Expression;
use Visionline\Crm\WebApi\Filter;
use Visionline\Crm\WebApi\Operator;

class ExpressionTest extends TestCase
{
    const FIELD = 'myField';
    const VALUE = 'test';

    public function testConstruct()
    {
        $expression = new Expression(
            self::FIELD,
            Operator::Eq,
            self::VALUE
        );

        $this->assertTrue($expression instanceof Filter);
        $this->assertSame(self::FIELD, $expression->field);
        $this->assertSame('Eq', $expression->op);
        $this->assertSame(self::VALUE, $expression->value);
    }
}
