<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model {

    protected $fillable = [
        'user_id', "agency"
    ];

}
