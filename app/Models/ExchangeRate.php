<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ExchangeRate extends Model
{
    use Notifiable;

    protected $table = 'currency_exchange_rates';

    //
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_from', 'currency_to', 'rate',
    ];

    /**
     * Currency conversion to USD
     * @param $amount
     * @param $rate
     * @return float
     */
    public static function exchangeCurrencyToBase($amount, $rate)
    {
        $base_amount = $amount / $rate;
        return round((float)$base_amount, 2);
    }
}
