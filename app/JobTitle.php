<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model {

    protected $fillable = [
        'user_id', "job_title"
    ];

}
