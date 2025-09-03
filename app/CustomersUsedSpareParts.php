<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomersUsedSpareParts extends Model {

    protected $fillable = [
        'user_id', "item_id", "item_name", "quantity", "price"
    ];

}
