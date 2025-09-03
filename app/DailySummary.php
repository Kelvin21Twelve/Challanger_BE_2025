<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailySummary extends Model {

    protected $table = 'daily_summary';
    protected $fillable = [
       "date", "cash", "knet","total"
    ];

}
