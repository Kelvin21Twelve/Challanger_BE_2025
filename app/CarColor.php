<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarColor extends Model {

    protected $fillable = [
        'user_id', "color"
    ];

}
