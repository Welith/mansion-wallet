<?php

namespace Tests\Unit;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * Test commision fee calculation when deposit fee is below fee maxima
     *
     * @return void
     */
    public function testCommissionFeeDepositNotMax()
    {
        $test_trans = new Transaction();
        $currency = 'USD';
        $amount = 100;
        $action = 'deposit';
        $fee = 0.0003;
        $return_fee = $test_trans::calcTransactionFee($amount, $fee, $currency, $action);
        $this->assertTrue($return_fee == 0.03);

    }
    /**
     * Test commision fee calculation when deposit fee is above fee maxima and currency is USD
     *
     * @return void
     */
    public function testCommissionFeeDepositMaxUSD()
    {
        $test_trans = new Transaction();
        $currency = 'USD';
        $amount = 100000;
        $action = 'deposit';
        $fee = 0.0003;
        $return_fee = $test_trans::calcTransactionFee($amount, $fee, $currency, $action);
        $this->assertFalse($return_fee > 5.55);
    }
    /**
     * Test commision fee calculation when deposit fee is above fee maxima and currency is GBP
     *
     * @return void
     */
    public function testCommissionFeeDepositMaxGBP()
    {
        $test_trans = new Transaction();
        $currency = 'GBP';
        $amount = 100000;
        $action = 'deposit';
        $fee = 0.0003;
        $return_fee = $test_trans::calcTransactionFee($amount, $fee, $currency, $action);
        $this->assertFalse($return_fee > 4.22);
    }
    /**
     * Test commision fee calculation when deposit fee is above fee maxima and currency is EUR
     *
     * @return void
     */
    public function testCommissionFeeDepositMaxEUR()
    {
        $test_trans = new Transaction();
        $currency = 'GBP';
        $amount = 100000;
        $action = 'deposit';
        $fee = 0.0003;
        $return_fee = $test_trans::calcTransactionFee($amount, $fee, $currency, $action);
        $this->assertFalse($return_fee > 5);
    }

/**
     * Test commision fee calculation when withdraw fee is above fee minima
     *
     * @return void
     */
    public function testCommissionFeeWithdrawNotMin()
    {
        $test_trans = new Transaction();
        $currency = 'USD';
        $amount = 200;
        $action = 'withdraw';
        $fee = 0.003;
        $return_fee = $test_trans::calcTransactionFee($amount, $fee, $currency, $action);
        $this->assertTrue($return_fee == 0.6);

    }
    /**
     * Test commision fee calculation when withdraw fee is above fee minima and currency is USD
     *
     * @return void
     */
    public function testCommissionFeeWithdrawMinUSD()
    {
        $test_trans = new Transaction();
        $currency = 'USD';
        $amount = 100;
        $action = 'withdraw';
        $fee = 0.003;
        $return_fee = $test_trans::calcTransactionFee($amount, $fee, $currency, $action);
        $this->assertFalse($return_fee < 0.55);
    }
    /**
     * Test commision fee calculation withdraw fee is above fee minima and currency is GBP
     *
     * @return void
     */
    public function testCommissionWithdrawMinGBP()
    {
        $test_trans = new Transaction();
        $currency = 'GBP';
        $amount = 100;
        $action = 'withdraw';
        $fee = 0.003;
        $return_fee = $test_trans::calcTransactionFee($amount, $fee, $currency, $action);
        $this->assertFalse($return_fee < 0.42);
    }
    /**
     * Test commision fee calculation when withdraw fee is above fee minima and currency is EUR
     *
     * @return void
     */
    public function testCommissionFeeWithdrawMinEUR()
    {
        $test_trans = new Transaction();
        $currency = 'EUR';
        $amount = 100;
        $action = 'withdraw';
        $fee = 0.003;
        $return_fee = $test_trans::calcTransactionFee($amount, $fee, $currency, $action);
        $this->assertFalse($return_fee < 0.5);
    }


}
