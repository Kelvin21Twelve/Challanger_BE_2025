<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labour extends Model {

    protected $fillable = [
        'user_id', "car_view", "car_type", "name", "price", "service_type", "apply_for_all", "print_adoption"
    ];

}
