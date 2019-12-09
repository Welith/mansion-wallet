<?php

namespace Tests\Unit;

use App\Models\ExchangeRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExchangeTest extends TestCase
{
    /**
     * Test EUR to USD conversion (USD to EUR -> 0.901388)
     *
     * @return void
     */
    public function testUSDEURExchange()
    {
        $test_excange = new ExchangeRate();
        $eur_amount = 1000;
        $rate = 0.901388;
        $usd_amount = $test_excange::exchangeCurrencyToBase($eur_amount, $rate);
        $this->assertTrue($usd_amount == 1109.40);
    }
    /**
     * Test GBP to USD conversion (USD to GBP -> 0.761249)
     *
     * @return void
     */
    public function testUSDGBPExchange()
    {
        $test_excange = new ExchangeRate();
        $gbp_amount = 1000;
        $rate = 0.761249;
        $usd_amount = $test_excange::exchangeCurrencyToBase($gbp_amount, $rate);
        $this->assertTrue($usd_amount == 1313.63);
    }
}
