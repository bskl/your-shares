<?php

namespace Tests\Feature;

use App\Traits\MoneyManager;
use Tests\TestCase;

class MoneyManagerTest extends TestCase
{
    use MoneyManager;

    /** @test */
    public function test_create_money()
    {
        $money = $this->createMoney('100.00');

        $this->assertObjectHasAttribute('amount', $money);
        $this->assertObjectHasAttribute('currency', $money);
        $this->assertEquals($money->getAmount(), '100');
        $this->assertEquals($money->getCurrency(), 'TRY');
    }

    /** @test */
    public function test_parse_by_intl_localized_decimal()
    {
        $money = $this->parseByIntlLocalizedDecimal('1.234,58');

        $this->assertObjectHasAttribute('amount', $money);
        $this->assertObjectHasAttribute('currency', $money);
        $this->assertEquals($money->getAmount(), '123458');
        $this->assertEquals($money->getCurrency(), 'TRY');

        $value = $this->parseByIntlLocalizedDecimal($money);

        $this->assertSame($value, $money);
    }

    /** @test */
    public function test_parse_by_decimal()
    {
        $money = $this->parseByDecimal('1234.58');

        $this->assertObjectHasAttribute('amount', $money);
        $this->assertObjectHasAttribute('currency', $money);
        $this->assertEquals($money->getAmount(), '123458');
        $this->assertEquals($money->getCurrency(), 'TRY');

        $value = $this->parseByDecimal($money);

        $this->assertSame($value, $money);
    }

    /** @test */
    public function test_format_by_intl()
    {
        $money = $this->parseByDecimal('1234.58');
        $formattedMoney = $this->formatByIntl($money);

        $this->assertEquals($formattedMoney, '₺1.234,58');
    }

    /** @test */
    public function test_get_trend()
    {
        $positiveMoney = $this->parseByDecimal('1234.58');
        $positiveTrend = $this->getTrend($positiveMoney);

        $this->assertEquals($positiveTrend, 1);

        $negativeMoney = $this->parseByDecimal('-1234.58');
        $negativeTrend = $this->getTrend($negativeMoney);

        $this->assertEquals($negativeTrend, -1);

        $zeroMoney = $this->createMoney();
        $trend = $this->getTrend($zeroMoney);

        $this->assertEquals($trend, 0);
    }
}
