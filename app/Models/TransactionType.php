<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TransactionType extends Model
{
    use Notifiable;

    protected $table = 'transaction_types';
}
