<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holyday extends Model {

    protected $fillable = [
        "user_id", "start_date", "end_date", "resume_date", "description", "is_delete"
    ];

}
