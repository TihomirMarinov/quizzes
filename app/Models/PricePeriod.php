<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricePeriod extends Model
{
    use HasFactory;

    protected $table = 'price_periods';

    const DEFAULT_PRICE = 5;
}
