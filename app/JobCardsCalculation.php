<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCardsCalculation extends Model {

    protected $fillable = [
        'user_id', "job_id", "cab_no", "used_spare_parts_total", "new_spare_parts_total", "labours_total", "grand_total", "balance"
    ];

}
