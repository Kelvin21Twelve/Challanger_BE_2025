<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SparePartsReturn extends Model {

    protected $table = 'spare_part_return';
    protected $fillable = [
       "item_code", "item", "quantity", "Price", "Discount", "Total", 'job_id'
    ];

}
