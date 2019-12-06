<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Currency extends Model
{
    use Notifiable;

    protected $table = 'currencies';
}
