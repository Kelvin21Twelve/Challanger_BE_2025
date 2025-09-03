<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VacType extends Model {
    protected $table = 'vac_types';
    protected $fillable = [
        'user_id', "name", "vac_limit", "is_payable", "description"
    ];

}
