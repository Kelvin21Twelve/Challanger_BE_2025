<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model {

    protected $fillable = [
        "user_id", "brand_name"
    ];

}
