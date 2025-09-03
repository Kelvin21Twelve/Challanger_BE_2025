<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarMake extends Model {

    protected $fillable = [
        'user_id', "make"
    ];

}
