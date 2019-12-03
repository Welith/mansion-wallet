<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Wallet
 * @package App
 */
class Transaction extends Model
{
    /**
     * Shows transaction-wallet relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo('App\Wallet');
    }
}
