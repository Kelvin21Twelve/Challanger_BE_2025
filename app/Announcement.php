<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model {

    protected $fillable = [
        "user_id", "description", "ann_date"
    ];

}
