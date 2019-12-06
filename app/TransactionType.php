<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TransactionType extends Model
{
    use Notifiable;

    protected $table = 'transaction_types';
}
