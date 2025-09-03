<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersWarnings extends Model {

    protected $table = 'users_warnings';
    public $primaryKey = "id";
    protected $fillable = [
        'user_id', 'on_date','description', 'is_delete'
    ];
}
