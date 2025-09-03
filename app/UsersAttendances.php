<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersAttendances extends Model {

    protected $table = 'users_attendances';
    public $primaryKey = "id";
    protected $fillable = [
         'on_date', 'in_time', 'out_time', 'is_delete', 'user_id'
    ];
}
