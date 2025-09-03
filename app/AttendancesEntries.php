<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendancesEntries extends Model {

    protected $table = 'attendances_entries';
    public $primaryKey = "id";
    protected $fillable = [
         'on_time', 'attendances_id', 'type'
    ];
}
