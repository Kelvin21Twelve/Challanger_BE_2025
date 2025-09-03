<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsedSpareParts extends Model {

    protected $fillable = [
        'user_id', 'item_name', 'car_view', 'car_type', 'sale_price', 'balance', 'min_limit'
    ];

}
