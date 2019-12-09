<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Wallet
 * @package App
 */
class Transaction extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_currency', 'amount'
    ];

    /**
     * Shows transaction-wallet relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo('App\Models\Wallet');
    }

    /**
     * Calculates comission fee based on parameters
     * @param $amount
     * @param $fee
     * @param $currency
     * @param $action
     * @return float|int
     */
    public static function calcTransactionFee($amount, $fee, $currency, $action)
    {
        $usd_to_eur_rate = ExchangeRate::where(['currency_to' => 'EUR'])->first()->rate;
        $usd_to_gbp_rate = ExchangeRate::where(['currency_to' => 'GBP'])->first()->rate;
        $min_withdraw_fee_usd = \Config::get('constants.fees.fee_withdraw_min_eur') / $usd_to_eur_rate;
        $min_withdraw_fee_gbp = $min_withdraw_fee_usd * $usd_to_gbp_rate;
        $max_deposit_fee_usd = \Config::get('constants.fees.fee_deposit_max_eur') / $usd_to_eur_rate;
        $max_deposit_fee_gbp = $max_deposit_fee_usd * $usd_to_gbp_rate;
        $comission_fee = $amount * $fee;
        if ($currency == 'USD') {
            if ($action == 'deposit') {
                $comission_fee = ($comission_fee <= $max_deposit_fee_usd) ? $comission_fee : $max_deposit_fee_usd;
            } else if ($action == 'withdraw') {
                $comission_fee = ($comission_fee >= $min_withdraw_fee_usd) ? $comission_fee : $min_withdraw_fee_usd;
            }
        } else if ($currency == 'EUR') {
            if ($action == 'deposit') {
                $comission_fee = ($comission_fee <= \Config::get('constants.fees.fee_deposit_max_eur')) ? $comission_fee : \Config::get('constants.fees.fee_deposit_max_eur');
            } else if ($action == 'withdraw') {
                $comission_fee = ($comission_fee >= \Config::get('constants.fees.fee_withdraw_min_eur')) ? $comission_fee : \Config::get('constants.fees.fee_withdraw_min_eur');
            }
        } else if ($currency == 'GBP') {
            if ($action == 'deposit') {
                $comission_fee = ($comission_fee <= $max_deposit_fee_gbp) ? $comission_fee : $max_deposit_fee_gbp;
            } else if ($action == 'withdraw') {
                $comission_fee = ($comission_fee >= $min_withdraw_fee_gbp) ? $comission_fee : $min_withdraw_fee_gbp;
            }
        }
        return round((float)$comission_fee, 2);
    }
}
