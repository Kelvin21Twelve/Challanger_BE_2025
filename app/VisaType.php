<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisaType extends Model {

    protected $fillable = [
        'user_id', "visa_type"
    ];

}
