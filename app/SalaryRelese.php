<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryRelese extends Model {

    protected $table = 'sal_rel';
    protected $fillable = [
       "type", "balance", "emp_id"
    ];

}
