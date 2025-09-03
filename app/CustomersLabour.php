<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomersLabour extends Model {

    protected $fillable = [
        'user_id', "job_id", "labour_id", "labour_name", "quantity", "price"
    ];

}
