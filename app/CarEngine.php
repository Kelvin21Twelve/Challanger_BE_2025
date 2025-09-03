<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarEngine extends Model
{
    protected $table="engine";
    protected $fillable = [
        'user_id', "make", "model", "engine_type"
    ];

    
}
