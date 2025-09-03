<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model {

    protected $table = 'payroll';
    protected $fillable = [
       "year", "month", "emp_id", "salary"
    ];

}
