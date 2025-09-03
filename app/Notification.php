<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    protected $fillable = [
        'user_id', "item_code", "item_name", "job_id", "job_card_status", "status","created_at"
    ];

}