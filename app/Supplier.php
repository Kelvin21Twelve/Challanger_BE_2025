<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {

    protected $fillable = [
        'user_id', "name", "phone", "fax", "profit_perc", "account_no", "address"
    ];

}
