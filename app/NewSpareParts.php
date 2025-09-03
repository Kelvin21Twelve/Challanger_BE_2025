<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewSpareParts extends Model {

    protected $fillable = [
        'user_id', 'item_code', 'item_name', 'item_unit', 'brand', 'agent_price', 'sale_price', 'balance', 'avg_cost', 'min_limit'
    ];

}
