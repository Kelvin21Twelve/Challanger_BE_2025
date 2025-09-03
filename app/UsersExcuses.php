<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersExcuses extends Model {

    protected $table = 'users_excuses';
    public $primaryKey = "id";
    protected $fillable = [
        'user_id', 'on_date', 'description', 'start_time', 'end_time', 'is_delete'
    ];
}
