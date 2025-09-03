<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomersNewSpareParts extends Model {

    protected $fillable = [
        'user_id', "job_id", "item_id", "item_code", "item", "quantity", "price", "discount", "total", "agent_price"
    ];

}
