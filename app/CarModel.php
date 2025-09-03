<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model {

    protected $fillable = [
        'user_id', 'make', 'from_model_year', 'model','to_model_year','engine_type','liter'
    ];

}
